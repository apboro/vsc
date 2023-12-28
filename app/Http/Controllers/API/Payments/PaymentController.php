<?php

namespace App\Http\Controllers\API\Payments;

use App\Helpers\PriceConverter;
use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use App\Models\Account\AccountTransaction;
use App\Models\Dictionaries\AccountTransactionType;
use App\Models\Dictionaries\InvoicePaymentStatus;
use App\Models\Dictionaries\InvoicePaymentType;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Invoices\Invoice;
use App\Models\Payment;
use App\Services\PayKeeperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentController extends Controller
{
    public function makePayment(Request $request)
    {
        $invoice = Invoice::where('hash', $request->hash)
            ->with([
                'contract',
                'contract.subscription',
                'contract.contractData',
                'contract.subscription.service'
            ])->first();

        $external_invoice = (new PayKeeperService($invoice->contract->subscription->service))->getInvoice($invoice)['body'];

        Payment::create([
            'gate' => 'tochka',
            'invoice_id' => $invoice->id,
            'amount' => $invoice->contract->contractData->price ?? $invoice->contract->contractData->monthly_price,
            'status_id' => InvoicePaymentStatus::created,
            'external_invoice_id' => $external_invoice['invoice_id'],
        ]);

        return redirect($external_invoice['invoice_url']);
    }

    /**
     * @throws Throwable
     */
    public function webhook(Request $request)
    {
        $invoice = Invoice::query()
            ->where('id', $request->orderid)
            ->where('status_id','<>', InvoiceStatus::paid)
            ->with([
                'contract',
                'contract.subscription',
                'contract.contractData',
                'contract.subscription.service',
                'contract.subscription.client.account'
            ])->first();
        $secret = $invoice->contract->subscription->service->acquiring->secret;
        $hash = md5($request->id . $secret);

        if (!$invoice){
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

    public function success()
    {
        return response()->view('payments.payment_success');
    }

    public function fail()
    {
        return response()->view('payments.payment_fail');
    }
}
