<?php

namespace App\Http\Controllers\API\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Dictionaries\InvoicePaymentStatus;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Invoices\Invoice;

class ExternalInvoiceController extends Controller
{
    public function index(string $hash)
    {
        $invoice = Invoice::where('hash', $hash)
            ->with([
                'contract',
                'contract.subscription',
                'contract.contractData',
                'contract.subscription.service'
            ])->first();

        if ($invoice->status_id === InvoiceStatus::paid){
            return response()->view('payments.payment_success', ['invoice' => $invoice]);
        }
        if ($invoice->contract->subscription->service->status_id !== ServiceStatus::enabled
            && $invoice->contract->subscription->service->end_at < now()){
            return response()->view('payments.payment_fail', ['invoice' => $invoice]);
        }
        return response()->view('payments/external_invoice', ['invoice' => $invoice]);
    }
}
