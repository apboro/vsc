<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Permissions\Permission;
use App\Models\Permissions\Role;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StaffRolesController extends ApiController
{
    /**
     * Get edit data for staff roles.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $id = $request->input('id');

        /** @var User $user */
        if ($id === null || null === ($user = User::query()->where('id', $id)->first())) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        $roles = $user->position->roles()->pluck('id')->toArray();
        $allRoles = Role::query()->where('active', true)->with('permissions')->orderBy('name')->get();

        $titles = [];
        $values = [];

        $permissions = [[]];
        $isAdmin = $user->position->roles()->whereIn('id', [Role::super])->count() > 0;

        foreach ($allRoles as $role) {
            /** @var Role $role */
            $titles["role.{$role->id}"] = $role->name;
            $values["role.{$role->id}"] = in_array($role->id, $roles, true);
            if (!$isAdmin && in_array($role->id, $roles, true)) {
                $permissions[] = $role->permissions()->pluck('key')->toArray();
            }
        }
        if ($isAdmin) {
            $permissions = Permission::query()->pluck('key')->toArray();
        } else {
            $permissions = array_merge(...$permissions);
        }

        // send response
        return APIResponse::form(
            $values,
            [],
            $titles,
            [
                // P.S. permissions inherited from roles
                'permissions' => $permissions,
            ]
        );
    }

    /**
     * Update staff roles.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $id = $request->input('id');

        /** @var User $user */
        if ($id === null || null === ($user = User::query()->where('id', $id)->first())) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        $data = Arr::undot($request->input('data'));
        $ids = array_filter($data['role'] ?? [], static function ($val) {
            return $val;
        });
        $ids = array_keys($ids);

        $user->position->roles()->sync($ids);

        // Get permissions inherited from roles
        $permissions = [];
        $isAdmin = $user->position->roles()->whereIn('id', [Role::super])->count() > 0;
        if ($isAdmin) {
            $permissions = Permission::query()->pluck('key')->toArray();
        } else {
            $roles = $user->position->roles()->with('permissions')->get();
            $permissions[] = [];
            foreach ($roles as $role) {
                /** @var Role $role */
                $permissions[] = $role->permissions->pluck('key')->toArray();
            }
            $permissions = array_merge(...$permissions);
        }

        return APIResponse::success(
            'Роли сотрудника обновлены',
            [
                'permissions' => $permissions,
            ]
        );
    }
}
