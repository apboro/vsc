<?php

namespace App\Http\Controllers\API\Payments;

use App\Helpers\PriceConverter;
use App\Http\Controllers\Controller;
use App\Models\Dictionaries\InvoicePaymentStatus;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Invoices\Invoice;
use App\Models\Payment;
use App\Services\PayKeeperService;
use Illuminate\Http\Request;

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

        $external_invoice = (new PayKeeperService())->getInvoice($invoice)['body'];

        Payment::create([
            'gate' => 'tochka',
            'invoice_id' => $invoice->id,
            'amount' => $invoice->contract->contractData->price ?? $invoice->contract->contractData->monthly_price,
            'status_id' => InvoicePaymentStatus::created,
            'external_invoice_id' =>$external_invoice['invoice_id'],
        ]);

        return redirect($external_invoice['invoice_url']);
    }

    public function webhook(Request $request)
    {
        $invoice = Invoice::findOrFail($request->orderid);
        $key = md5($invoice->payment->external_id
            .$invoice->id
            .number_format($invoice->amount_to_pay, 2)
            .$invoice->contract->contractData->getClientFullName()
            .config('paykeeper.secret'));
        if ($key !== $request->key){
            echo $key;
            return;
        }
        $payment = Payment::findOrFail('invoice_id', $invoice->id);
        $payment->external_id = $request->id;
        $payment->status_id = InvoicePaymentStatus::paid;
        $payment->save();

        $invoice->amount_paid = $request->sum;
        $invoice->paid_at = now();
        $invoice->status_id = InvoiceStatus::paid;
        $invoice->payment_status_id = InvoicePaymentStatus::paid;
        $invoice->save();
        echo 'very well!';
    }
}
