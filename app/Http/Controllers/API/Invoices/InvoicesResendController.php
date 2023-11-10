<?php

namespace App\Http\Controllers\API\Invoices;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Invoices\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoicesResendController extends ApiController
{
    public function resend(Request $request): JsonResponse
    {
        /** @var Invoice|null $invoice */
        $invoice = Invoice::query()->find($request->input('id'));

        if (!$invoice) {
            return APIResponse::notFound('Счет не найден.');
        }

        $invoice->sendToClient();

        return APIResponse::success('Счет отправлен клиенту');
    }
}