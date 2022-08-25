<?php

namespace App\Http\Controllers\API\Leads;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Leads\Lead;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class LeadsListController extends ApiController
{
    protected array $defaultFilters = [
//        'status_id' => ServiceStatus::enabled,
        'training_base_id' => null,
        'sport_kind_id' => null,
    ];

    protected array $rememberFilters = [
//        'status_id',
        'training_base_id',
        'sport_kind_id',
    ];

    protected string $rememberKey = CookieKeys::leads_list;

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

        $query = Lead::query()
            ->tap(new ForOrganization($current->organizationId()))
            ->with([
                'status',
                'service',
                'subscription.service',
                'service.trainingBase',
                'subscription.service.trainingBase',
                'service.sportKind',
                'subscription.service.sportKind',
                'subscription.client.user.profile',
            ])
            ->orderBy('created_at', 'desc');

        // apply filters
        if (!empty($filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey))) {
//            if (!empty($filters['status_id'])) {
//                $query->where('status_id', $filters['status_id']);
//            }
//            if (!empty($filters['training_base_id'])) {
//                $query->where('training_base_id', $filters['training_base_id']);
//            }
//            if (!empty($filters['sport_kind_id'])) {
//                $query->where('sport_kind_id', $filters['sport_kind_id']);
//            }
        }

        // apply search
//        if (!empty($search = $request->search())) {
//            foreach ($search as $term) {
//                $query->where(function (Builder $query) use ($term) {
//                    $query->where('title', 'LIKE', "%$term%");
//                });
//            }
//        }

        // current page automatically resolved from request via `page` parameter
        /** @var LengthAwarePaginator $leads */
        $leads = $query->paginate($request->perPage(10, $this->rememberKey));

        $leads->transform(function (Lead $lead) {
            if ($lead->subscription) {
                $service = $lead->subscription->service->title;
                $trainingBase = $lead->subscription->service->trainingBase->title;
                $sportKind = $lead->subscription->service->sportKind->name;
                $needHelp = false;
            } else {
                $service = $lead->service->title ?? null;
                $trainingBase = $lead->service ? $lead->service->trainingBase->short_title : null;
                $sportKind = $lead->service ? $lead->service->sportKind->name : null;
                $needHelp = $lead->need_help;
            }

            return [
                'id' => $lead->id,
                'lastname' => $lead->lastname,
                'firstname' => $lead->firstname,
                'patronymic' => $lead->patronymic,
                'status' => $lead->status->name,

                'service' => $service,
                'training_base' => $trainingBase,
                'sport_kind' => $sportKind,
                'need_help' => $needHelp,

                'client' => $lead->subscription && $lead->subscription->client ? $lead->subscription->client->user->profile->compactName : null,
                'client_id' => $lead->subscription ? $lead->subscription->client_id : null,
                'created_date' => $lead->created_at->format('d.m.Y'),
                'created_time' => $lead->created_at->format('H:i'),
            ];
        });

        return APIResponse::list(
            $leads,
            ['Дата', 'ФИО', 'Статус', 'Услуга', 'Клиент'],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
