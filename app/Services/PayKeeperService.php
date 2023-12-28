<?php

namespace App\Services;

use App\Exceptions\PayKeeperGetException;
use App\Models\Invoices\Invoice;
use App\Models\Services\Service;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayKeeperService
{
    private string $login;
    private string $password;
    private string $server;

    public function __construct(private Service $service)
    {
        $acquiring = $this->service->acquiring;
        $this->login = $acquiring->login;
        $this->password = $acquiring->password;
        $this->server = $acquiring->server;
    }

    /**
     * @throws PayKeeperGetException
     */
    public function get(string $uri, array $query = []): array
    {
        try {
            $response = Http::withBasicAuth($this->login, $this->password)
                ->timeout(3)
                ->get($this->server . $uri, $query);
        } catch (Exception $e) {
            Log::channel('paykeeper')->error($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            throw new PayKeeperGetException();
        }

        return [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->json(),
        ];
    }

    public function post(string $uri, array $data = []): array
    {
        $response = Http::withBasicAuth($this->login, $this->password)
            ->timeout(3)
            ->asForm()
            ->post($this->server . $uri, $data);

        return [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->json(),
        ];
    }

    public function getToken()
    {
        try {
            return $this->get('/info/settings/token/')['body']['token'];
        } catch (Exception $e) {
            Log::channel('paykeeper')->error($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            throw new PayKeeperGetException();
        }
    }

    public function getInvoice(Invoice $invoice): array
    {
        $data = $this->createInvoiceQuery($invoice);
        return $this->post('/change/invoice/preview/', $data);
    }

    public function createInvoiceQuery(Invoice $invoice): array
    {
        return [
            'token' => $this->getToken(),
            "pay_amount" => $invoice->contract->contractData->price ?? $invoice->contract->contractData->monthly_price,
            "clientid" => $invoice->contract->contractData->getClientFullName(),
            "orderid" => $invoice->id,
            "client_email" => $invoice->contract->contractData->email,
            "service_name" => $invoice->contract->subscription->service->title,
            "client_phone" => $invoice->contract->contractData->phone,
        ];
    }

    public function checkPaymentStatus(Invoice $invoice)
    {
        $query = $invoice->payment->external_invoice_id;
        return $this->get('/info/invoice/byid/'."?id=$query");
    }
}
