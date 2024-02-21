<?php

namespace App\Services;

use App\Models\Invoices\Invoice;
use App\Models\Services\Service;
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
use YooKassa\Request\Payments\CreatePaymentResponse;

class YoukassaService
{
    private Client $client;

    public function __construct(private Service $service)
    {
        $this->client = new Client();
        $this->client->setAuth($this->service->acquiring->login, $this->service->acquiring->secret);
    }

    /**
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws ApiException
     * @throws ExtensionNotFoundException
     * @throws BadApiRequestException
     * @throws AuthorizeException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws ApiConnectionException
     * @throws UnauthorizedException
     */
    public function createPayment(Invoice $invoice): ?CreatePaymentResponse
    {
        $data = $this->createInvoiceQuery($invoice);

        return $this->client->createPayment($data);
    }

    public function createInvoiceQuery(Invoice $invoice): array
    {
        return [
            'amount' => [
                'value' => $invoice->contract->contractData->price ?? $invoice->contract->contractData->monthly_price,
                'currency' => 'RUB',
            ],
            'confirmation' => [
                'type' => 'redirect',
                'locale' => 'ru_RU',
                'return_url' => 'https://vsc.dev2.site-master.su/invoice/payment/success',
            ],
            'capture' => true,
            'description' => 'Счёт № ' . $invoice->id,
            'metadata' => [
                'orderNumber' => $invoice->id
            ],
            'receipt' => [
                'customer' => [
                    'full_name' => $invoice->contract->contractData->getClientFullName(),
                    'email' => $invoice->contract->contractData->email,
                    'phone' => preg_replace('/[^0-9]/', '', $invoice->contract->contractData->phone),
                ],
                'items' => [
                    [
                        'description' => $invoice->contract->subscription->service->title,
                        'quantity' => '1.00',
                        'amount' => [
                            'value' => $invoice->contract->contractData->price ?? $invoice->contract->contractData->monthly_price,
                            'currency' => 'RUB'
                        ],
                        'vat_code' => '1',
                    ],
                ]
            ]
        ];
    }

}
