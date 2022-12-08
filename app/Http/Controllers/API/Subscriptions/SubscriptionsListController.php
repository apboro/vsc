<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
     * Get subscriptions list.
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

        $filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey);

        $query = $this->getListQuery($request, $clientId, $filters, $current);

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
                'contracts' => $subscription->contracts->map(function (SubscriptionContract $contract) {
                    return [
                        'title' => 'Договор №' . $contract->number,
                        'start_at' => $contract->start_at ? $contract->start_at->format('d.m.Y') : null,
                        'end_at' => $contract->end_at ? $contract->end_at->format('d.m.Y') : null,
                        'discount_name' => $contract->discount ? $contract->discount->name : null,
                        'discount' => $contract->discount ? $contract->discount->discount : null,
                        'monthly_price' => $contract->contractData->monthly_price,
                    ];
                }),
            ];
        });

        return APIResponse::list(
            $subscriptions,
            ['ID', 'Статус', 'Клиент / Занимающийся', 'Договор', 'Услуга', 'Объект'],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }

    /**
     * Export subscriptions to excel.
     *
     * @param APIListRequest $request
     *
     * @return  JsonResponse
     */
    public function export(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        $this->rememberKey = CookieKeys::getKey($this->rememberKey, $current->organizationId());

        $clientId = $request->input('client_id');

        $filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey);

        $query = $this->getListQuery($request, $clientId, $filters, $current);

        $subscriptions = $query->get();

        $now = Carbon::now();

        $titles = [
            'ID подписки',
            'ID клиента',
            'ФИО клиента',
            'ФИО занимающегося',
            'Статус',
            'Договор',
            'Услуга',
            'Вид спорта',
            'Объект',
            'Адрес',
            'Дата создания ЛИДА',
            'Район',
        ];

        /** @var Collection $subscriptions */
        $subscriptions->transform(function (Subscription $subscription) {
            $contracts = '';

            $subscription->contracts->map(function (SubscriptionContract $contract) use (&$contracts) {
                $contracts .= implode('; ', array_filter([
                    'Договор №' . $contract->number,
                    ($contract->start_at ? $contract->start_at->format('d.m.Y') : null) . '-' . ($contract->end_at ? $contract->end_at->format('d.m.Y') : '—'),
                    $contract->discount ? $contract->discount->discount . '% ' . $contract->discount->name : null,
                    $contract->contractData->monthly_price . 'руб./мес.',
                ]));
            });

            return [
                'id' => $subscription->id,
                'client_id' => $subscription->client_id,
                'client' => $subscription->client->user->profile->fullName,
                'ward' => $subscription->clientWard->user->profile->fullName,
                'status' => $subscription->status->name,
                'contracts' => $contracts,
                'service' => "{$subscription->service->title} ($subscription->service_id)",
                'sport_kind' => $subscription->service->sportKind->name,
                'training_base' => $subscription->service->trainingBase->short_title ?? $subscription->service->trainingBase->title,
                'training_base_address' => $subscription->service->trainingBase->info->address,
                'date_lead_created_at' => $subscription->lead && $subscription->lead->created_at ? $subscription->lead->created_at->format('d.m.Y, H:i') : null,
                'district' => $subscription->service->trainingBase->region->name ?? '—',
            ];
        });

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0)->setTitle('Подписки')->setShowRowColHeaders(true);

        $spreadsheet->getActiveSheet()->fromArray($titles, '—', 'A1');
        $spreadsheet->getActiveSheet()->fromArray($subscriptions->toArray(), '—', 'A2');
        foreach(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K','L'] as $col) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        ob_start();
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $export = ob_get_clean();

        return APIResponse::response([
            'file' => base64_encode($export),
            'file_name' => 'Подписки ' . $now->format('Y-m-d H:i'),
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
    protected function getListQuery(ApiListRequest $request, ?int $clientId, array $filters, Current $current): Builder
    {
        $query = Subscription::query()
            ->tap(new ForOrganization($current->organizationId()))
            ->with(['status', 'service', 'service.trainingBase', 'service.sportKind', 'client.user.profile', 'clientWard.user.profile'])
            ->with('contracts', function (HasMany $query) {
                $query
                    ->where('status_id', SubscriptionContractStatus::accepted)
                    ->with(['contractData', 'discount']);
            })
            ->when($clientId, function (Builder $query) use ($clientId) {
                $query->whereHas('client', function (Builder $query) use ($clientId) {
                    $query->where('id', $clientId);
                });
            })
            ->orderBy('created_at', 'desc');

        // apply filters
        if (!empty($filters)) {
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

        return $query;
    }
}
