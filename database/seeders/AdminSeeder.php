<?php

namespace Database\Seeders;

use App\Models\Permissions\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create admin user
        /** @var User $admin */
        if (User::query()->where('id', 1)->count() === 0) {
            $admin = User::factory()->create(['login' => 'admin', 'password' => Hash::make('admin')]);
            $admin->position()->create(['title_id' => null]);
            $admin->position->roles()->attach(Role::super);
            $admin->profile()->create(['lastname' => 'Администратор', 'firstname' => 'Администратор', 'gender' => 'male', 'email' => 'admin@admin.admin']);
        }
    }
}
