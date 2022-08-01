<?php

namespace App\Http\Controllers\API\Services;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Service\Service;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ServicesListController extends ApiController
{
    protected array $defaultFilters = [
        'status_id' => ServiceStatus::enabled,
        'training_base_id' => null,
        'sport_kind_id' => null,
    ];

    protected array $rememberFilters = [
        'status_id',
        'training_base_id',
        'sport_kind_id',
    ];

    protected string $rememberKey = CookieKeys::services_list;

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

        $query = Service::query()
            ->tap(new ForOrganization($current->organizationId()))
            ->with(['status', 'trainingBase', 'sportKind'])
            ->orderBy('created_at', 'desc');

        // apply filters
        if (!empty($filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey))) {
            if (!empty($filters['status_id'])) {
                $query->where('status_id', $filters['status_id']);
            }
            if (!empty($filters['training_base_id'])) {
                $query->where('training_base_id', $filters['training_base_id']);
            }
            if (!empty($filters['sport_kind_id'])) {
                $query->where('sport_kind_id', $filters['sport_kind_id']);
            }
        }

        // apply search
        if (!empty($search = $request->search())) {
            foreach ($search as $term) {
                $query->where(function (Builder $query) use ($term) {
                    $query->where('title', 'LIKE', "%$term%");
                });
            }
        }

        // current page automatically resolved from request via `page` parameter
        /** @var LengthAwarePaginator $services */
        $services = $query->paginate($request->perPage(10, $this->rememberKey));

        $services->transform(function (Service $service) {
            return [
                'id' => $service->id,
                'active' => $service->hasStatus(ServiceStatus::enabled),
                'title' => $service->title,
                'sport_kind' => $service->sportKind->name,
                'training_base' => $service->trainingBase->short_title,
            ];
        });

        return APIResponse::list(
            $services,
            ['ID', 'Название', 'Вид спорта', 'Объект'],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
