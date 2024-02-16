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
use Illuminate\Http\Request;
use Log;

class YoukassaWebhookController extends Controller
{
    /**
     * @throws AccountException
     */
    public function __invoke(Request $request)
    {
        Log::info('youkassa webhook', [$request->all()]);
        $invoice = Invoice::query()
            ->whereHas('payment',
                fn ($q) => $q->where('external_payment_id', $request['object']['id']))
            ->with([
                'contract',
                'contract.subscription',
                'contract.contractData',
                'contract.subscription.service',
                'contract.subscription.client.account'
            ])->first();

        if ($invoice->status_id === InvoicePaymentStatus::paid){
            return response("OK", 200);
        }

        $account = $invoice->contract->subscription->client->account;
        if (!$account->id){
            $account = $invoice->contract->subscription->client->account()->create();
        }

        $account->attachTransaction(new AccountTransaction([
            'type_id' => AccountTransactionType::account_refill_card,
            'amount' => $request['object']['amount']['value'],
            'timestamp' => now(),
            'invoice_id' => $invoice->id,
            'comments' => 'Пополнение для оплаты счёта № ' . $invoice->id,
        ]));

        $account->attachTransaction(new AccountTransaction([
            'type_id' => AccountTransactionType::account_withdrawal_pay_for_service,
            'amount' => $request['object']['amount']['value'],
            'timestamp' => now(),
            'invoice_id' => $invoice->id,
            'comments' => 'Оплата счёта № ' . $invoice->id,
        ]));

        $invoice->amount_paid = $request['object']['amount']['value'];
        $invoice->paid_at = now();
        $invoice->status_id = InvoiceStatus::paid;
        $invoice->payment_status_id = InvoicePaymentStatus::paid;
        $invoice->payment_type_id = InvoicePaymentType::acquiring;
        $invoice->save();

        return response("OK", 200);


    }
}
