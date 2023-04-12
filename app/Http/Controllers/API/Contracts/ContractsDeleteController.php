<?php

namespace App\Http\Controllers\API\Contracts;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\Contracts;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractsDeleteController extends ApiController
{
    /**
     * Delete organization.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->input('id');

        /** @var Contracts $contract */
        if ($id === null || null === ($contract = Contracts::query()->where('id', $id)->first())) {
            return APIResponse::notFound('Организация не найдена');
        }

        $name = $contract->name;

        try {
            $contract->delete();
        } catch (QueryException $exception) {
            return APIResponse::error('Невозможно удалить договор. Есть блокирующие связи.');
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success("Договор \"$name\" удален");
    }
}
