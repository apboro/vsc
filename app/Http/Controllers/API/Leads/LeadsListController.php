<?php

namespace App\Http\Controllers\API\Leads;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Leads\Lead;
use App\Models\Services\Service;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LeadsListController extends ApiController
{
    protected array $defaultFilters = [
        'status_id' => null,
        'training_base_id' => null,
        'sport_kind_id' => null,
        'region_id' => null,
        'type_program_id' => null,
    ];

    protected array $rememberFilters = [
        'status_id',
        'training_base_id',
        'sport_kind_id',
        'region_id',
        'type_program_id',
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

        $filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey);

        $query = $this->getListQuery($request, $filters, $current);

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
                'ward_lastname' => $lead->ward_lastname,
                'ward_firstname' => $lead->ward_firstname,
                'ward_patronymic' => $lead->ward_patronymic,
                'client_lastname' => $lead->lastname,
                'client_firstname' => $lead->firstname,
                'client_patronymic' => $lead->patronymic,
                'status' => $lead->status->name,

                'service' => $service,
                'training_base' => $trainingBase,
                'sport_kind' => $sportKind,
                'need_help' => $needHelp,

                'client' => $lead->subscription && $lead->subscription->client ? $lead->subscription->client->user->profile->compactName : null,
                'client_id' => $lead->subscription ? $lead->subscription->client_id : null,
                'ward' => $lead->subscription && $lead->subscription->clientWard ? $lead->subscription->clientWard->user->profile->compactName : null,
                'ward_id' => $lead->subscription ? $lead->subscription->client_ward_id : null,
                'created_date' => $lead->created_at->format('d.m.Y'),
                'created_time' => $lead->created_at->format('H:i'),
            ];
        });

        return APIResponse::list(
            $leads,
            ['Дата', 'ФИО занимающегося', 'ФИО представителя', 'Статус', 'Услуга'],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }

    public function export(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        $this->rememberKey = CookieKeys::getKey($this->rememberKey, $current->organizationId());

        $filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey);

        $query = $this->getListQuery($request, $filters, $current);

        $leads = $query->get();
        $now = Carbon::now();

        $titles = [
            'ID лида',
            'ФИО клиента',
            'Дата создания ЛИДА',
            'Статус',
            'Район',
            'Адрес (объекта)',
            'Услуга',
            'Дата конвертации лида',
            'Год рождения ребенка',
            'Телефон родителя',
            'Комментарий',
        ];

        /** @var Collection $leads */
        $leads->transform(function (Lead $lead) {
            if ($lead->subscription) {
                $service = $lead->subscription->service->title;
                $trainingBase = $lead->subscription->service->trainingBase;
            } else {
                $service = $lead->service->title ?? "—";
                $trainingBase = $lead->service ? $lead->service->trainingBase : null;
            }

            return [
                'id' => $lead->id,
                'client_FIO' => "$lead->lastname $lead->firstname $lead->patronymic",
                'created_date' => $lead->created_at->format('d.m.Y'),
                'status' => $lead->status->name,
                'region' => $lead->region ? $lead->region->name : "—",
                'training_base' => $trainingBase ? $trainingBase->info->address : "—",
                'service' => $service,
                'lead_converted_to_client' => $lead->converted_at ? $lead->converted_at->format('d.m.Y') : "—",
                'ward_birth_date' => $lead->ward_birth_date ? $lead->ward_birth_date->format('Y') : null,
                'parent_phone' => $lead->phone,
                'comment' => $lead->comments,
            ];
        });

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0)->setTitle('Лиды')->setShowRowColHeaders(true);

        $spreadsheet->getActiveSheet()->fromArray($titles, '—', 'A1');
        $spreadsheet->getActiveSheet()->fromArray($leads->toArray(), '—', 'A2');
        foreach(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'] as $col) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        ob_start();
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $export = ob_get_clean();

        return APIResponse::response([
            'file' => base64_encode($export),
            'file_name' => 'Лиды ' . $now->format('Y-m-d H:i'),
            'type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Make subscriptions query with filters and search.
     *
     * @param APIListRequest $request
     * @param int|null $clientId
     * @param array $filters
     * @param Current $current
     *
     * @return  Builder
     */
    protected function getListQuery(ApiListRequest $request, array $filters, Current $current): Builder
    {
        $query = Lead::query()
            ->tap(new ForOrganization($current->organizationId()))
            ->with([
                'status',
                'service',
                'subscription.service',
                'service.trainingBase',
                'subscription.service.trainingBase',
                'service.sportKind',
                'service.sportKinds',
                'subscription.service.sportKind',
                'subscription.client.user.profile',
            ])
            ->orderBy('created_at', 'desc');

        // apply filters
        if (!empty($filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey))) {
            if (!empty($filters['status_id'])) {
                $query->where('status_id', $filters['status_id']);
            }
            if (!empty($filters['training_base_id'])) {
                $query->whereHas('service', function (Builder $query) use ($filters) {
                    $query->whereIn('training_base_id', $filters['training_base_id']);
                });
            }
            if (!empty($filters['sport_kinds'])) {
                $query->whereHas('service.sportKinds', function (Builder $query) use ($filters) {
                    $query->whereIn('id', $filters['sport_kinds']);

                });
            }
            if (!empty($filters['region_id'])) {
                $query->whereIn('region_id', $filters['region_id']);
            }
            if (!empty($filters['type_program_id'])) {
                $query->whereHas('service', function (Builder $q) use ($filters) {
                    $q->where('type_program_id', $filters['type_program_id']);
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
                        ->where('lastname', 'LIKE', "%$term%")
                        ->orWhere('firstname', 'LIKE', "%$term%")
                        ->orWhere('patronymic', 'LIKE', "%$term%")
                        ->where('ward_lastname', 'LIKE', "%$term%")
                        ->orWhere('ward_firstname', 'LIKE', "%$term%")
                        ->orWhere('ward_patronymic', 'LIKE', "%$term%")

                        ->orWhere('phone', 'LIKE', "%$term%")
                        ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(phone, '-',''), ' ',''), '(',''), ')','') LIKE ?", ['%' . $term . '%']);
                });
            }
        }

        return  $query;
    }

    public function getServicesForFilter(): JsonResponse
    {
        /** @var Collection<Service> $services */
        $services = Service::query()
            ->select('id', 'title')
            ->get();

        return APIResponse::success(null, $services->toArray());
    }
}
