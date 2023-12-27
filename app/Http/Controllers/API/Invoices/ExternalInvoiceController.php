<?php

namespace App\Http\Controllers\API\Invoices;

use App\Http\Controllers\Controller;
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

        return response()->view('payments/external_invoice', ['invoice' => $invoice]);
    }
}
