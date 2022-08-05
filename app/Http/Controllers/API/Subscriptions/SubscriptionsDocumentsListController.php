<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionsDocumentsListController extends ApiController
{
    protected string $rememberKey = CookieKeys::subscriptions_documents_list;

    /**
     * Get services list.
     *
     * @param APIListRequest $request
     *
     * @return  JsonResponse
     */
    public function list(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        $this->rememberKey = CookieKeys::getKey($this->rememberKey, $current->organizationId());

        $subscriptionId = $request->input('id');

        $query = SubscriptionContract::query()
            ->whereHas('subscription', function (Builder $query) use ($current, $subscriptionId) {
                $query
                    ->where('id', $subscriptionId)
                    ->tap(new ForOrganization($current->organizationId()));
            })
            ->with(['status', 'subscription'])
            ->orderBy('created_at', 'desc');

        // current page automatically resolved from request via `page` parameter
        /** @var LengthAwarePaginator $contracts */
        $contracts = $query->paginate($request->perPage(10, $this->rememberKey));

        $contracts->transform(function (SubscriptionContract $contract) use ($current) {
            return [
                'id' => $contract->id,
                'title' => 'Договор',
                'status' => $contract->status->name,
                'start_at' => $contract->start_at ? $contract->start_at->format('d.m.Y') : null,
                'end_at' => $contract->end_at ? $contract->end_at->format('d.m.Y') : null,
                'is_acceptable' => $contract->hasStatus(SubscriptionContractStatus::draft) && $current->can('subscriptions.accept.document'),
            ];
        });

        return APIResponse::list(
            $contracts,
            ['ID', 'Документ', 'Статус', 'Дата создания', 'Дата окончания'],
            [],
            [],
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
