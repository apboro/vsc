<?php

namespace App\Http\Controllers\API\Invoices;

use App\Current;
use App\Helpers\PriceConverter;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Account\AccountTransaction;
use App\Models\Clients\Client;
use App\Models\Dictionaries\AccountTransactionType;
use App\Models\Dictionaries\InvoicePaymentStatus;
use App\Models\Dictionaries\InvoicePaymentType;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Dictionaries\InvoiceType;
use App\Models\Dictionaries\ServiceTypes;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Invoices\Invoice;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        'comment' => 'required_if:type_id,' . InvoiceType::recalculation . '|string',

        'trainings_attended' => 'required_if:type_id,' . InvoiceType::recalculation . '|integer|min:1',
        'one_time_discount' => 'required_if:type_id,' . InvoiceType::recalculation . '|integer|min:1|max:100',
        'recalc_method' => 'required_if:type_id,' . InvoiceType::recalculation . '|integer|exists:dictionary_invoice_types,id',
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
        'trainings_attended' => 'Занятий посещено',
        'one_time_discount' => 'Разовая скидка, %',
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

            //  Функция редактирования доступна только для неоплаченных счетов
            if (!$invoice->isEditable()) {
                return APIResponse::error('Можно отредактировать только неоплаченный счет.');
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

            'trainings_attended' => $invoice->trainings_attended,
            'one_time_discount' => $invoice->one_time_discount,
            // Стоимость занятия при перерасчете: базовая или перерасчетная
            'recalc_method' => $invoice->recalc_method ?? InvoiceType::recalculation,
            'amount_to_pay' => $invoice->amount_to_pay,
//            'pay_with_account' => $invoice->transactions()->count() > 0,

            'paid_from_account' => PriceConverter::storeToPrice($invoice->transactions()->sum('amount')),
        ];

        $payload = [
            'first_day_of_last_month' => new Carbon('first day of last month'),
            'last_day_of_last_month' => new Carbon('last day of last month'),
            'subscriptions' => $client->subscriptions()
                ->where('status_id', SubscriptionStatus::sent)
                ->whereHas('service', function ($q) {
                    $q->whereHas('typeProgram', function ($q) {
                        $q->where('service_type_id', ServiceTypes::regular);
                    });
                })
                ->get()
                ->transform(function (Subscription $s) {
                return [
                    'id' => $s->id,
                    'name' => $s->service->title . ".<br> Занимающийся: " . $s->clientWard->user->profile->fullName,
                    'contracts' => $s->contractsActive->transform(function (SubscriptionContract $c) {
                        $service = $c->subscription->service;
                        $trainingsCount = $service->trainings_per_month;
                        $trainingPrice = $service->training_price;
                        $trainingPriceRecalc = $service->training_return_price;
                        $discount = $c->discount;

                        $total = $c->calculateBaseInvoiceTotal();
                        return [
                            'id' => $c->id,
                            'name' => 'Договор от ' . $c->start_at->format('d.m.Y'),
                            'start_at' => $c->start_at,
                            'trainings_count' => $trainingsCount,
                            'training_price' => $trainingPrice,
                            'training_price_recalc' => $trainingPriceRecalc,
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
                    //  Функция редактирования доступна только для неоплаченных счетов
                    if (!$invoice->isEditable()) {
                        throw new BadRequestHttpException();
                    }
                } else {
                    //  Создаем новый счет
                    $invoice = new Invoice();
                }

                //  Обновляем/сохраняем данные
                $invoice->date_from = $data['date_from'];
                $invoice->date_to = $data['date_to'];
                $invoice->moderation_required = false;
                $invoice->contract_id = $data['contract_id'];
                $invoice->status_id = InvoiceStatus::ready;
                $invoice->type_id = $data['type_id'];
                $invoice->amount_to_pay = $data['amount_to_pay'];
                $invoice->comment = $data['comment'];

                //  Параметры расчета при перерасчете
                if ($data['type_id'] === InvoiceType::recalculation) {
                    $invoice->trainings_attended = $data['trainings_attended'];
                    $invoice->one_time_discount = $data['one_time_discount'];
                    $invoice->recalc_method = $data['recalc_method'];
                }

                $invoice->save();
                $invoice->refresh();

                //  Если стоит чекбокс оплатить с лицевого счета списываем оттуда.
                $accountBalance = $client->account->amount;
                if (
                    !empty($data['pay_with_account']) &&
                    $accountBalance > 0 &&
                    $data['id'] === null  //  чекбокс активен только в момент создания счета
                ) {
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
                        $invoice->payment_status_id = InvoicePaymentStatus::paid;
                    //  Иначе пересчитываем сумму к оплате
                    } else {
                        $invoice->amount_to_pay -= $withdraw;
                        $invoice->payment_status_id = InvoicePaymentStatus::partially_paid;
                    }

                    //  Сохраняем счет
                    $invoice->save();
                }
            });
        } catch (BadRequestHttpException $e) {
            return APIResponse::error('Можно отредактировать только неоплаченный счет.');
        }
        catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Счет добавлен.');
    }

}