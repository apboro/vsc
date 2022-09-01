<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Subscriptions\Subscription;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionsListController extends ApiController
{
    protected array $defaultFilters = [
        'status_id' => null,
        'training_base_id' => null,
        'service_id' => null,
    ];

    protected array $rememberFilters = [
        'status_id',
        'training_base_id',
        'service_id',
    ];

    protected string $rememberKey = CookieKeys::subscriptions_list;

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

        $clientId = $request->input('client_id');

        $query = Subscription::query()
            ->tap(new ForOrganization($current->organizationId()))
            ->with(['status', 'service', 'service.trainingBase', 'service.sportKind', 'client.user.profile', 'clientWard.user.profile'])
            ->when($clientId, function (Builder $query) use ($clientId) {
                $query->whereHas('client', function (Builder $query) use ($clientId) {
                    $query->where('id', $clientId);
                });
            })
            ->orderBy('created_at', 'desc');

        // apply filters
        if (!empty($filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey))) {
            if (!empty($filters['status_id'])) {
                $query->where('status_id', $filters['status_id']);
            }
            if (!empty($filters['training_base_id'])) {
                $query->whereHas('service', function (Builder $query) use ($filters) {
                    $query->where('training_base_id', $filters['training_base_id']);
                });
            }
            if (!empty($filters['service_id'])) {
                $query->where('service_id', $filters['service_id']);
            }
        }

        // apply search
        if (!empty($search = $request->search())) {
            foreach ($search as $term) {
                $query->where(function (Builder $query) use ($term) {
                    $query
                        ->whereHas('client', function (Builder $query) use ($term) {
                            $query->whereHas('user', function (Builder $query) use ($term) {
                                $query->whereHas('profile', function (Builder $query) use ($term) {
                                    $query->where('lastname', 'LIKE', "%$term%")
                                        ->orWhere('firstname', 'LIKE', "%$term%")
                                        ->orWhere('patronymic', 'LIKE', "%$term%");
                                });
                            });
                        })
                        ->orWhereHas('clientWard', function (Builder $query) use ($term) {
                            $query->whereHas('user', function (Builder $query) use ($term) {
                                $query->whereHas('profile', function (Builder $query) use ($term) {
                                    $query->where('lastname', 'LIKE', "%$term%")
                                        ->orWhere('firstname', 'LIKE', "%$term%")
                                        ->orWhere('patronymic', 'LIKE', "%$term%");
                                });
                            });
                        });
                });
            }
        }

        // current page automatically resolved from request via `page` parameter
        /** @var LengthAwarePaginator $subscriptions */
        $subscriptions = $query->paginate($request->perPage(10, $this->rememberKey));

        $subscriptions->transform(function (Subscription $subscription) {
            return [
                'id' => $subscription->id,
                'client' => $subscription->client->user->profile->fullName,
                'client_id' => $subscription->client_id,
                'ward' => $subscription->clientWard->user->profile->fullName,
                'ward_id' => $subscription->client_ward_id,
                'status' => $subscription->status->name,
                'service' => $subscription->service->title,
                'service_id' => $subscription->service_id,
                'training_base' => $subscription->service->trainingBase->short_title ?? $subscription->service->trainingBase->title,
                'training_base_address' => $subscription->service->trainingBase->info->address,
                'sport_kind' => $subscription->service->sportKind->name,
            ];
        });

        return APIResponse::list(
            $subscriptions,
            ['ID', 'Статус', 'Занимающийся', 'Клиент', 'Услуга', 'Объект'],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
