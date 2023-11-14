<?php

namespace App\Http\Controllers\API\Invoices;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Clients\Client;
use App\Models\Invoices\Invoice;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoicesListController extends ApiController
{
    protected array $defaultFilters = [
    ];

    protected array $rememberFilters = [
    ];

    protected string $rememberKey = CookieKeys::transactions_list;

    public function list(APIListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var Client|null $client */
        $client = Client::query()
            ->with([
                'subscriptions.contractsActive.invoices',
            ])
            ->tap(new ForOrganization($current->organizationId(), true))
            ->find($request->input('client_id'));

        if (!$client) {
            return APIResponse::notFound('Клиент не найден');
        }

        $contractIds = $client->subscriptions
            ->pluck('contractsActive')
            ->flatten()
            ->pluck('id');

        /** @var LengthAwarePaginator<Invoice> $invoices */
        $invoices = Invoice::query()
            ->with([
                'contract.subscription.service',
                'contract.subscription.clientWard.user.profile',
                'status',
                'paymentType'
            ])
            ->whereIn('contract_id', $contractIds)
            ->paginate($request->perPage(10, $this->rememberKey));

        $invoices->getCollection()->transform(function (Invoice $invoice) use ($current) {
            return [
                'id' => $invoice->id,
                'created_at' => $invoice->created_at->format('d.m.Y'),
                'subscription' => $invoice->contract->subscription->service->title,
                'ward' => $invoice->contract->subscription->clientWard->user->profile->fullName,
                'dates' => $invoice->date_from->format('d.m.Y') . ' - ' . $invoice->date_to->format('d.m.Y'),
                'amount_to_pay' => $invoice->amount_to_pay,
                'amount_paid' => $invoice->amount_paid,
                'status' => $invoice->status->name,
                'payment_status' => $invoice->paymentStatus->name,
                'paid_at' => $invoice->paid_at ? $invoice->paid_at->format('d.m.Y') : null,
                'payment_type' => $invoice->paymentType->name ?? null,
                'comment' => $invoice->comment,

                'can_edit' => $invoice->isEditable() && $current->can('invoices.edit'),
                'can_remove' => $invoice->isEditable() && $current->can('invoices.remove'),
                'can_resend' => $invoice->isPayable(),
                'can_pay_by_account' => $invoice->isPayable() && $current->can('invoices.update'),
            ];
        });

        return APIResponse::list(
            $invoices,
            [
                '№ Счета',
                'Дата выставления',
                'Подписка / Занимающийся',
                'Период',
                'Сумма к оплате',
                'Статус оплаты / Дата Оплаты',
                'Сумма платежа',
                'Способ оплаты',
            ])->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}