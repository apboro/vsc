<?php

namespace App\Http\Controllers\API\Staff;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Permissions\Permission;
use App\Models\Permissions\Role;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StaffPermissionsController extends ApiController
{
    /**
     * Get edit data for staff permissions.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var User $user */
        if ($id === null || null === ($user = User::query()
                ->where('id', $id)
                ->whereHas('position', function (Builder $query) use ($current) {
                    $query->tap(new ForOrganization($current->organizationId(), true));
                })
                ->first()
            )) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        $permissions = Permission::query()->get();
        $staffPermissions = $user->position->permissions()->pluck('id')->toArray();
        $modules = [];
        $titles = [];
        $values = [];

        foreach ($permissions as $permission) {
            /** @var Permission $permission */
            $titles["permission.$permission->key"] = $permission->name;
            $values["permission.$permission->key"] = in_array($permission->id, $staffPermissions, true);
            if (!array_key_exists($permission->module, $modules)) {
                $modules[$permission->module] = __("modules.$permission->module");
            }
        }
        asort($modules);

        // send response
        return APIResponse::form(
            $values,
            [],
            $titles,
            [
                'modules' => $modules,
            ]
        );
    }

    /**
     * Update excursion data.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var User $user */
        if ($id === null || null === ($user = User::query()
                ->where('id', $id)
                ->whereHas('position', function (Builder $query) use ($current) {
                    $query->tap(new ForOrganization($current->organizationId(), true));
                })
                ->first()
            )) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        // Get permissions inherited from roles
        $rolePermissions = [];
        $isAdmin = $user->position->roles()->whereIn('id', [Role::super])->count() > 0;
        if ($isAdmin) {
            $rolePermissions = Permission::query()->pluck('id')->toArray();
        } else {
            $roles = $user->position->roles()->with('permissions')->get();
            $rolePermissions[] = [];
            foreach ($roles as $role) {
                /** @var Role $role */
                $rolePermissions[] = $role->permissions->pluck('id')->toArray();
            }
            $rolePermissions = array_merge(...$rolePermissions);
        }

        $data = $request->input('data');

        $keys = array_filter($data, static function ($value) {
            return $value;
        });

        $keys = array_map(function ($permission) {
            return substr($permission, strlen('permission.'));
        }, array_keys($keys));

        $ids = Permission::query()->whereIn('key', $keys)->pluck('id')->toArray();

        $set = $user->position->permissions()->pluck('id')->toArray();

        $protected = array_intersect($set, $rolePermissions);

        $ids = array_filter($ids, static function ($value) use ($rolePermissions) {
            return !in_array($value, $rolePermissions, true);
        });

        $ids = array_merge($ids, $protected);

        $user->position->permissions()->sync($ids);

        $modules = [];
        foreach (Permission::query()->orderBy('name')->distinct()->pluck('module')->toArray() as $module) {
            $modules[$module] = __("modules.$module");
        }
        asort($modules);

        return APIResponse::success(
            'Права сотрудника обновлены',
            [
                'modules' => $modules,
            ]
        );
    }
}
