<?php

namespace App\Http\Controllers\API\Payments;

use App\Exceptions\Account\AccountException;
use App\Http\Controllers\Controller;
use App\Models\Account\AccountTransaction;
use App\Models\Dictionaries\AccountTransactionType;
use App\Models\Dictionaries\InvoicePaymentStatus;
use App\Models\Dictionaries\InvoicePaymentType;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Invoices\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaykeeperWebhookController extends Controller
{
    /**
     * @throws AccountException
     */
    public function __invoke(Request $request)
    {
        $invoice = Invoice::query()
            ->where('id', $request->orderid)
            ->with([
                'contract',
                'contract.subscription',
                'contract.contractData',
                'contract.subscription.service',
                'contract.subscription.client.account'
            ])->first();
        $secret = $invoice->contract->subscription->service->acquiring->secret;
        $hash = md5($request->id . $secret);

        if ($invoice->status_id === InvoicePaymentStatus::paid){
            return response("OK $hash", 200);
        }
        $key = md5($request->id
            . number_format($invoice->amount_to_pay, 2,".", "")
            . $invoice->contract->contractData->getClientFullName()
            . $invoice->id
            . $secret);
        if ($key !== $request->key) {
            Log::channel('paykeeper')->error('Hash mismatch', ['request' => $request, 'invoice' => $invoice]);
            exit;
        }
        Payment::query()->where(['invoice_id' => $invoice->id])
            ->update([
                'external_payment_id' => $request->id,
                'status_id' => InvoicePaymentStatus::paid,
            ]);

        $account = $invoice->contract->subscription->client->account;
        if (!$account->id){
            $account = $invoice->contract->subscription->client->account()->create();
        }

        $account->attachTransaction(new AccountTransaction([
            'type_id' => AccountTransactionType::account_refill_card,
            'amount' => $request->sum,
            'timestamp' => now(),
            'invoice_id' => $invoice->id,
            'comments' => 'Пополнение для оплаты счёта № ' . $invoice->id,
        ]));

        $account->attachTransaction(new AccountTransaction([
            'type_id' => AccountTransactionType::account_withdrawal_pay_for_service,
            'amount' => $request->sum,
            'timestamp' => now(),
            'invoice_id' => $invoice->id,
            'comments' => 'Оплата счёта № ' . $invoice->id,
        ]));

        $invoice->amount_paid = $request->sum;
        $invoice->paid_at = now();
        $invoice->status_id = InvoiceStatus::paid;
        $invoice->payment_status_id = InvoicePaymentStatus::paid;
        $invoice->payment_type_id = InvoicePaymentType::acquiring;
        $invoice->save();

        return response("OK $hash", 200);
    }
}
