<?php

namespace App\Http\Controllers\API\TrainingBase;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\SportKind;
use App\Models\Dictionaries\TrainingBaseStatus;
use App\Models\TrainingBase\TrainingBase;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class TrainingBaseListController extends ApiController
{
    protected array $defaultFilters = [
        'status_id' => TrainingBaseStatus::enabled,
        'sport_kinds' => [],
    ];

    protected array $rememberFilters = [
        'status_id',
        'sport_kinds',
    ];

    protected string $rememberKey = CookieKeys::training_base_list;

    /**
     * Get positions list.
     *
     * @param APIListRequest $request
     *
     * @return  JsonResponse
     */
    public function list(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        $query = TrainingBase::query()
            ->tap(new ForOrganization($current->organizationId()))
            ->with(['status', 'info', 'sportKinds'])
            ->orderBy('created_at', 'desc');

        // apply filters
        if (!empty($filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey))) {
            if (!empty($filters['status_id'])) {
                $query->where('status_id', $filters['status_id']);
            }
            if (!empty($filters['sport_kinds'])) {
                $query->whereHas('sportKinds', function (Builder $query) use ($filters) {
                    $query->whereIn('id', $filters['sport_kinds']);
                });
            }
        }

        // apply search
        if (!empty($search = $request->search())) {
            foreach ($search as $term) {
                $query->where(function (Builder $query) use ($term) {
                    $query
                        ->whereHas('info', function (Builder $query) use ($term) {
                            $query->where('email', 'LIKE', "%$term%")
                                ->orWhere('phone', 'LIKE', "%$term%")
                                ->orWhere('address', 'LIKE', "%$term%");
                        })
                        ->orWhere('title', 'LIKE', "%$term%");
                });
            }
        }

        // current page automatically resolved from request via `page` parameter
        /** @var LengthAwarePaginator $bases */
        $bases = $query->paginate($request->perPage(10, $this->rememberKey));

        $bases->transform(function (TrainingBase $base) {
            return [
                'id' => $base->id,
                'active' => $base->hasStatus(TrainingBaseStatus::enabled),
                'title' => $base->title,
                'address' => $base->info->address,
                'email' => $base->info->email,
                'phone' => $base->info->phone,
                'sport_kinds' => $base->sportKinds->map(function (SportKind $kind) {
                    return $kind->name;
                }),
            ];
        });

        return APIResponse::list(
            $bases,
            [
                'Название объекта', 'Виды спорта', 'Контакты',
            ],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
