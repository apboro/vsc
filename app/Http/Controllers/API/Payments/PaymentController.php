<?php

namespace App\Http\Controllers\API\Payments;

use App\Helpers\PriceConverter;
use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use App\Models\Account\AccountTransaction;
use App\Models\Dictionaries\AccountTransactionType;
use App\Models\Dictionaries\Bank;
use App\Models\Dictionaries\InvoicePaymentStatus;
use App\Models\Dictionaries\InvoicePaymentType;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Invoices\Invoice;
use App\Models\Payment;
use App\Services\PayKeeperService;
use App\Services\YoukassaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;
use YooKassa\Client;
use YooKassa\Common\Exceptions\ApiConnectionException;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;

class PaymentController extends Controller
{
    /**
     * @throws NotFoundException
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws AuthorizeException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ApiConnectionException
     */
    public function makePayment(Request $request)
    {
        $invoice = Invoice::where('hash', $request->hash)
            ->with([
                'contract',
                'contract.subscription',
                'contract.contractData',
                'contract.subscription.service',
                'contract.subscription.service.acquiring',
                'contract.subscription.service.acquiring.bank',
            ])->first();

        $service = $invoice->contract->subscription->service;

        if ($service->acquiring->bank->id === Bank::TOCHKA) {
            $response = (new PayKeeperService($service))
                ->createPayment($invoice);
            $payment_url = $response['invoice_url'];
            $external_id = $response['invoice_id'];
        }

        if ($service->acquiring->bank->id === Bank::SBERBANK) {
            $response = (new YoukassaService($service))
                ->createPayment($invoice);
            $payment_url = $response->getConfirmation()->getConfirmationUrl();
            $external_id = $response->id;
        }

        Payment::create([
            'gate' => $service->acquiring->bank->name,
            'invoice_id' => $invoice->id,
            'amount' => $invoice->contract->contractData->price ?? $invoice->contract->contractData->monthly_price,
            'status_id' => InvoicePaymentStatus::created,
            'external_payment_id' => $external_id ?? null,
        ]);

        return redirect($payment_url ?? '');
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
