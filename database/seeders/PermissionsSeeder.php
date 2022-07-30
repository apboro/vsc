<?php

namespace Database\Seeders;

use App\Models\Permissions\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{

    protected array $permissions = [
        'system' => [],
        'dictionaries' => [
            'dictionaries.edit' => 'Редактирование справочников',
        ],
        // 'roles' => [
        //     'roles.edit' => 'Редактирование ролей',
        // ],
        'staff' => [
            'staff.view' => 'Просмотр карточек сотрудников',
            'staff.edit' => 'Добавление и редактирование карточек сотрудников',
            'staff.delete' => 'Удаление карточек сотрудников',
            'staff.access' => 'Управление доступом сотрудников',
            'staff.permissions' => 'Управление правами сотрудников',
        ],
        'training_base' => [
            'training_base.view' => 'Просмотр объектов',
            'training_base.edit' => 'Добавление и редактирование объектов',
            'training_base.delete' => 'Удаление объектов',
            'training_base.contracts.view' => 'Просмотр договоров',
            'training_base.contracts.modify' => 'Добавление, редактирование, удаление договоров',
        ],
        'organizations' => [
            'organizations.view' => 'Просмотр карточек организаций',
            'organizations.edit' => 'Добавление и редактирование карточек организаций',
            'organizations.delete' => 'Удаление карточек организаций',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (empty($this->permissions)) {
            Permission::query()->delete();

        } else {
            $processed = [];

            foreach ($this->permissions as $module => $permissions) {
                if (empty($permissions)) {
                    Permission::query()->where('module', $module)->delete();
                } else {
                    foreach ($permissions as $key => $name) {
                        $permission = Permission::query()->where('key', $key)->first();
                        if ($permission === null) {
                            $permission = new Permission();
                            $permission->key = $key;
                        }
                        $permission->module = $module;
                        $permission->name = $name;
                        $permission->save();
                        $processed[] = $permission->id;
                    }
                }
            }

            Permission::query()->whereNotIn('id', $processed)->delete();
        }
    }
}
