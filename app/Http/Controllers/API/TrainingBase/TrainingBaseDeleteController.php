<?php

namespace App\Http\Controllers\API\TrainingBase;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\TrainingBase\TrainingBase;
use App\Scopes\ForOrganization;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingBaseDeleteController extends ApiController
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

        /** @var TrainingBase $base */
        if ($id === null || null === ($base = TrainingBase::query()->where('id', $id)->tap(new ForOrganization($current->organizationId()))->first())) {
            return APIResponse::notFound('Объект не найден');
        }

        $name = $base->title;

        try {
            $base->delete();
        } catch (QueryException $exception) {
            return APIResponse::error('Невозможно удалить объект. Есть блокирующие связи.');
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success("Объект \"$name\" удалён");
    }
}
