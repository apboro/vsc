<?php

namespace App\Http\Controllers\API\Invoices;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Account\AccountTransaction;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Invoices\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicesRemoveController extends ApiController
{
    public function remove(Request $request): JsonResponse
    {
        /** @var Invoice|null $invoice */
        $invoice = Invoice::query()->find($request->input('data.id'));

        if (!$invoice) {
            return APIResponse::notFound('Счет не найден.');
        }

        if (in_array($invoice->status_id, [InvoiceStatus::sent, InvoiceStatus::paid])) {
            return APIResponse::forbidden('Нельзя удалить отправленный или оплаченный счет.');
        }

        DB::transaction(function () use ($invoice, $request) {
            /** @var AccountTransaction $transaction */
            foreach ($invoice->transactions as $transaction) {
                $invoice->contract->subscription->client->account->detachTransaction($transaction);
            }

            $invoice->status_id = InvoiceStatus::cancelled;
            $invoice->delete_comment = $request->input('data.delete_comment');
            $invoice->save();
        });

        return APIResponse::success('Счет аннулирован');
    }
}