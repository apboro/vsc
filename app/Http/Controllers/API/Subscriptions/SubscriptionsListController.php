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
        'sport_kind_id' => null,
    ];

    protected array $rememberFilters = [
        'status_id',
        'training_base_id',
        'sport_kind_id',
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

        $query = Subscription::query()
            ->tap(new ForOrganization($current->organizationId()))
            ->with(['status', 'service', 'service.trainingBase', 'service.sportKind', 'client.user.profile'])
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
            if (!empty($filters['sport_kind_id'])) {
                $query->whereHas('service', function (Builder $query) use ($filters) {
                    $query->where('sport_kind_id', $filters['sport_kind_id']);
                });
            }
        }

        // apply search
        if (!empty($search = $request->search())) {
            foreach ($search as $term) {
                $query->where(function (Builder $query) use ($term) {
                    $query->whereHas('client', function (Builder $query) use ($term) {
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
                'status' => $subscription->status->name,
                'service' => $subscription->service->title,
                'training_base' => $subscription->service->trainingBase->short_title,
                'sport_kind' => $subscription->service->sportKind->name,
            ];
        });

        return APIResponse::list(
            $subscriptions,
            ['ID', 'Статус', 'Клиент', 'Вид спорта', 'Объект'],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
