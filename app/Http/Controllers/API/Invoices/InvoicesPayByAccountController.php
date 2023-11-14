<?php

namespace App\Http\Controllers\API\Invoices;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Account\AccountTransaction;
use App\Models\Dictionaries\AccountTransactionType;
use App\Models\Dictionaries\InvoicePaymentStatus;
use App\Models\Dictionaries\InvoicePaymentType;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Invoices\Invoice;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicesPayByAccountController extends ApiController
{
    public function payByAccount(Request $request): JsonResponse
    {
        /** @var Invoice|null $invoice */
        $invoice = Invoice::query()->find($request->input('id'));

        if (!$invoice) {
            return APIResponse::notFound('Счет не найден.');
        }

        if (!$invoice->isPayable()) {
            return APIResponse::error('Нельзя оплатить черновик счета, оплаченный или аннулированный счет.');
        }

        $current = Current::get($request);

        DB::transaction(function () use ($invoice, $current) {
            $account = $invoice->contract->subscription->client->account;

            //  Вычисляем, сколько необходимо внести для оплаты счета
            $amountToRefill = $invoice->amount_to_pay - $account->amount;

            //  Если сумма больше нуля - вносим сумму
            if ($amountToRefill > 0) {
                $account->attachTransaction(
                    new AccountTransaction([
                        'invoice_id' => $invoice->id,
                        'type_id' => AccountTransactionType::account_refill_cash,
                        'timestamp' => Carbon::now(),
                        'amount' => $amountToRefill,
                        'reason' => null,
                        'reason_date' => null,
                        'committer_id' => $current->positionId(),
                        'comments' => 'Пополнение для оплаты счета №' . $invoice->id,
                    ]),
                    true
                );
            }

            //  Списываем сумму, необходимую для оплаты
            $account->attachTransaction(
                new AccountTransaction([
                    'invoice_id' => $invoice->id,
                    'type_id' => AccountTransactionType::account_withdrawal_cash,
                    'timestamp' => Carbon::now(),
                    'amount' => $invoice->amount_to_pay,
                    'reason' => null,
                    'reason_date' => null,
                    'committer_id' => $current->positionId(),
                    'comments' => 'Оплата счета №' . $invoice->id,
                ]),
                true
            );

            $invoice->amount_paid = $invoice->amount_to_pay;
            $invoice->status_id = InvoiceStatus::paid;
            $invoice->paid_at = Carbon::now();
            $invoice->payment_type_id = InvoicePaymentType::cash;
            $invoice->payment_status_id = InvoicePaymentStatus::paid;

            $invoice->save();
        });

        return APIResponse::success('Счет оплачен');
    }
}