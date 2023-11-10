<?php

namespace App\Http\Controllers\API\Invoices;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Account\AccountTransaction;
use App\Models\Clients\Client;
use App\Models\Dictionaries\AccountTransactionType;
use App\Models\Dictionaries\InvoicePaymentType;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Invoices\Invoice;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicesAddController extends ApiEditController
{
    protected array $rules = [
        'id' => 'nullable|integer|exists:invoices,id',
        'subscription_id' => 'required|integer|exists:subscriptions,id',
        'contract_id' => 'required|integer|exists:subscription_contracts,id',
        'type_id' => 'required|integer|exists:dictionary_invoice_types,id',
        'date_from' => 'required|date|before_or_equal:date_to',
        'date_to' => 'required|date|after_or_equal:date_from',
        'amount_to_pay' => 'required|integer',
        'comment' => 'nullable|string',
    ];

    protected array $titles = [
        'created_at' => 'Дата создания',
        'client' => 'Клиент',
        'subscription_id' => 'Подписка',
        'contract_id' => 'Контракт',
        'type_id' => 'Тип оплаты',
        'date_from' => 'С',
        'date_to' => 'По',
        'comment' => 'Комментарий',
    ];

    /**
     * Get data for invoice.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var Client|null $client */
        $client = Client::query()
            ->where('id', $request->input('client_id'))
            ->tap(new ForOrganization($current->organizationId(), true))
            ->first();

        if ($client === null) {
            return APIResponse::notFound('Клиент не найден');
        }

        /** @var Invoice $invoice */
        if ($invoiceId = $request->input('invoice_id')) {
            $invoice = Invoice::query()->find($invoiceId);

            if (!$invoice) {
                return APIResponse::notFound('Счет не найден');
            }
        } else {
            $invoice = new Invoice();
        }

        $values = [
            'id' => $invoice->id,
            'created_at' => $invoice->created_at ?: Carbon::now(),
            'client' => $client->user->profile->fullName ?? null,
            'subscription_id' => $invoice->contract->subscription_id ?? null,
            'contract_id' => $invoice->contract_id,
            'type_id' => $invoice->type_id,
            'date_from' => $invoice->date_from,
            'date_to' => $invoice->date_to,
            'comment' => $invoice->comment,
        ];

        $payload = [
            'first_day_of_last_month' => new Carbon('first day of last month'),
            'last_day_of_last_month' => new Carbon('last day of last month'),
            'subscriptions' => $client->subscriptions->transform(function (Subscription $s) {
                return [
                    'id' => $s->id,
                    'name' => $s->service->title . '. Занимающийся: ' . $s->clientWard->user->profile->fullName,
                    'contracts' => $s->contractsActive->transform(function (SubscriptionContract $c) {
                        $service = $c->subscription->service;
                        $trainingsCount = $service->trainings_per_month; // todo recalc if type === recalculate
                        $trainingPrice = $service->training_price;
                        $discount = $c->discount;

                        $total = $c->calculateBaseInvoiceTotal();  // todo recalc if type === recalculate
                        return [
                            'id' => $c->id,
                            'name' => 'Договор от ' . $c->start_at->format('d.m.Y'),
                            'trainings_count' => $trainingsCount,
                            'training_price' => $trainingPrice,
                            'discount' => $discount->name ?? 'Нет',
                            'total_price' => $total,
                        ];
                    })
                ];
            })
        ];

        $this->titles['pay_with_account'] = 'Оплатить с лицевого счета (доступно ' . $client->account->amount . ' р.)';

        $this->rules['date_from'] = 'required|date';
        $this->rules['date_to'] = 'required|date';

        return APIResponse::form(
            $values,
            $this->rules,
            $this->titles,
            $payload
        );
    }

    /**
     * Update invoice data.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var Client|null $client */
        $client = Client::query()
            ->with(['user.profile', 'wards.user.profile'])
            ->where('id', $request->input('client_id'))
            ->tap(new ForOrganization($current->organizationId(), true))
            ->first();

        if ($client === null) {
            return APIResponse::notFound('Клиент не найден');
        }

        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        try {
            DB::transaction(static function () use ($client, $data, $current) {
                /** @var Invoice $invoice */
                if ($data['id']) {
                    //  Получаем счет для обновления данных
                    $invoice = Invoice::query()->find($data['id']);
                    //  Сохраняем актуальную сумму к оплате на момент создания счета
                    $amountToPay = $invoice->amount_to_pay;
                } else {
                    //  Создаем новый счет
                    $invoice = new Invoice();
                    //  Используем рассчитанную в методе get() сумму к оплате
                    $amountToPay = $data['amount_to_pay'];
                }

                //  Обновляем/сохраняем данные
                $invoice->date_from = $data['date_from'];
                $invoice->date_to = $data['date_to'];
                $invoice->moderation_required = false;
                $invoice->contract_id = $data['contract_id'];
                $invoice->status_id = InvoiceStatus::ready;
                $invoice->type_id = $data['type_id'];
                $invoice->amount_to_pay = $amountToPay;
                $invoice->comment = $data['comment'];

                $invoice->save();
                $invoice->refresh();

                //  Если стоит галочка оплатить с лицевого счета списываем оттуда.
                $accountBalance = $client->account->amount;
                if (!empty($data['pay_with_account']) && $accountBalance > 0) {
                    //  Списываем сумму к оплате, если средств на ЛС достаточно, иначе - всю доступную сумму
                    $withdraw = $invoice->amount_to_pay > $accountBalance ? $accountBalance : $invoice->amount_to_pay;

                    //  Создаем транзакцию
                    $client->account->attachTransaction(
                        new AccountTransaction([
                            'invoice_id' => $invoice->id,
                            'type_id' => AccountTransactionType::account_withdrawal_cash,
                            'timestamp' => Carbon::now(),
                            'amount' => $withdraw,
                            'reason' => null,
                            'reason_date' => null,
                            'committer_id' => $current->positionId(),
                            'comments' => 'Оплата счета №' . $invoice->id,
                        ]),
                        true
                    );

                    //  Если денег на ЛС хватило для полной оплаты переводим счет в статус оплачено
                    if ($withdraw === $invoice->amount_to_pay) {
                        $invoice->status_id = InvoiceStatus::paid;
                        $invoice->paid_at = Carbon::now();
                        $invoice->amount_paid = $withdraw;
                        $invoice->payment_type_id = InvoicePaymentType::cash;
                    //  Иначе пересчитываем сумму к оплате
                    } else {
                        $invoice->amount_to_pay -= $withdraw;
                    }

                    //  Сохраняем счет
                    $invoice->save();
                }
            });
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Счет добавлен.');
    }

}