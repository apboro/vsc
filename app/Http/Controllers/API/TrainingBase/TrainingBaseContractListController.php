<?php

namespace App\Http\Controllers\API\TrainingBase;

use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Common\File;
use App\Models\Dictionaries\TrainingBaseContractStatus;
use App\Models\TrainingBase\TrainingBase;
use App\Models\TrainingBase\TrainingBaseContract;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class TrainingBaseContractListController extends ApiController
{
    protected array $defaultFilters = [
        'status_id' => TrainingBaseContractStatus::active,
    ];

    protected array $rememberFilters = [
        'status_id',
    ];

    protected string $rememberKey = CookieKeys::training_base_contracts_list;

    /**
     * Get positions list.
     *
     * @param APIListRequest $request
     *
     * @return  JsonResponse
     */
    public function list(ApiListRequest $request): JsonResponse
    {
        $baseId = $request->input('base_id');

        /** @var TrainingBase $base */
        if ($baseId === null ||
            null === ($base = TrainingBase::query()
                ->where('id', $baseId)
                ->first())
        ) {
            return APIResponse::notFound('Объект не найен');
        }

        $query = $base->contracts();

        // apply filters
        if (!empty($filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey)) && !empty($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }

        /** @var LengthAwarePaginator $contracts */
        $contracts = $query->paginate($request->perPage(10, $this->rememberKey));
        $now = Carbon::now();

        $contracts->transform(function (TrainingBaseContract $contract) use ($now) {
            if ($contract->hasStatus(TrainingBaseContractStatus::inactive)) {
                $active = false;
            } else {
                $active = ($contract->start_at <= $now && $contract->end_at >= $now) ? true : null;
            }
            return [
                'id' => $contract->id,
                'active' => $active,
                'start_date' => $contract->start_at->format('d.m.Y'),
                'end_date' => $contract->end_at->format('d.m.Y'),
                'start_at' => $contract->start_at->format('Y-m-d'),
                'end_at' => $contract->end_at->format('Y-m-d'),
                'status' => $contract->status->name,
                'status_id' => $contract->status_id,
                'files' => $contract->files->map(function (File $file) {
                    return [
                        'id' => $file->id,
                        'name' => $file->original_filename,
                        'url' => $file->url,
                        'type' => $file->mime,
                        'size' => $file->size,
                    ];
                }),
            ];
        });

        return APIResponse::list(
            $contracts,
            [
                'Период действия', 'Статус', 'Файлы',
            ],
            $filters,
            $this->defaultFilters
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
