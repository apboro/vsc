<?php

namespace App\Http\Controllers\API\Roles;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Permissions\Permission;
use App\Models\Permissions\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RolesEditController extends ApiEditController
{
    protected array $rules = [
        'name' => 'required',
        'active' => 'required',
    ];

    protected array $titles = [
        'name' => 'Название',
        'description' => 'Описание',
        'active' => 'Статус',
    ];

    /**
     * Get edit data for role.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        /** @var Role|null $role */
        $role = $this->firstOrNew(Role::class, $request, ['permissions']);

        if ($role === null) {
            return APIResponse::notFound('Роль не найена');
        }

        if ($role->locked === true) {
            return APIResponse::error('Эту роль нельзя редактировать');
        }

        $values = [
            'name' => $role->name ?? null,
            'description' => $role->description ?? null,
            'active' => $role->active ?? null,
        ];

        $permissions = Permission::query()->orderBy('name')->get();
        $rolePermissions = $role->permissions()->pluck('id')->toArray();
        $modules = [];

        foreach ($permissions as $permission) {
            /** @var Permission $permission */
            $this->titles["permission.{$permission->key}"] = $permission->name;
            $values["permission.{$permission->key}"] = in_array($permission->id, $rolePermissions, true);
            if (!array_key_exists($permission->module, $modules)) {
                $modules[$permission->module] = __("modules.$permission->module");
            }
        }
        asort($modules);

        // send response
        return APIResponse::form(
            $values,
            $this->rules,
            $this->titles,
            [
                'title' => $role->exists ? $role->name : 'Добавление роли',
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
        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        /** @var Role|null $role */
        $role = $this->firstOrNew(Role::class, $request);

        if ($role === null) {
            return APIResponse::notFound('Роль не найена');
        }

        if ($role->locked === true) {
            return APIResponse::error('Эту роль нельзя редактировать');
        }

        $role->name = $data['name'];
        $role->description = $data['description'];
        $role->active = $data['active'];
        $role->save();

        $raw = Arr::undot($data);
        $keys = array_key_exists('permission', $raw) ? Arr::dot($raw['permission']) : [];
        $keys = array_filter($keys, static function ($value) {
            return $value;
        });
        $keys = array_keys($keys);
        $ids = Permission::query()->whereIn('key', $keys)->pluck('id')->toArray();
        $role->permissions()->sync($ids);

        $modules = [];
        foreach (Permission::query()->orderBy('name')->distinct()->pluck('module')->toArray() as $module) {
            $modules[$module] = __("modules.$module");
        }
        asort($modules);

        return APIResponse::success(
            $role->wasRecentlyCreated ? 'Роль добавлена' : 'Данные роли обновлены',
            [
                'id' => $role->id,
                'title' => $role->name,
                'modules' => $modules,
            ]
        );
    }
}
