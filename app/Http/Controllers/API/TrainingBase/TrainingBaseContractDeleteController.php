<?php

namespace App\Http\Controllers\API\TrainingBase;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\TrainingBase\TrainingBaseContract;
use App\Scopes\ForOrganization;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingBaseContractDeleteController extends ApiController
{
    /**
     * Delete training base.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var TrainingBaseContract $contract */
        if ($id === null || null === (
            $contract = TrainingBaseContract::query()
                ->where('id', $id)
                ->whereHas('trainingBase', function (Builder $query) use ($current) {
                    $query->tap(new ForOrganization($current->organizationId()));
                })
                ->first()
            )) {
            return APIResponse::notFound('Документ не найден');
        }

        $name = $contract->start_at->format('d.m.Y') . ' - ' . $contract->end_at->format('d.m.Y');

        try {
            $contract->delete();
        } catch (QueryException $exception) {
            return APIResponse::error('Невозможно удалить документ. Есть блокирующие связи.');
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success("Документ \"$name\" удалён");
    }
}
