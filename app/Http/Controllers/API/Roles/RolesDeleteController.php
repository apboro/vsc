<?php

namespace App\Http\Controllers\API\Roles;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Permissions\Role;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesDeleteController extends ApiController
{
    /**
     * Delete role.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->input('id');

        /** @var Role $role */
        if ($id === null || null === ($role = Role::query()->where('id', $id)->first())) {
            return APIResponse::notFound('Роль не найена');
        }

        if ($role->locked) {
            return APIResponse::error('Эту роль нельзя удалить');
        }

        $name = $role->name;

        try {
            $role->delete();
        } catch (QueryException $exception) {
            return APIResponse::error('Невозможно удалить роль. Есть блокирующие связи.');
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success(    "Роль \"$name\" удалёна");
    }
}
