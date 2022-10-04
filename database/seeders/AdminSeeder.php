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
        /** @var User|null $admin */
        $admin = User::query()->where('id', 1)->first();

        if ($admin === null) {
            /** @var User $admin */
            $admin = User::factory()->create(['login' => 'admin', 'password' => Hash::make('admin')]);
            $admin->position()->create(['title_id' => null]);
            $admin->position->roles()->attach(Role::super);
            $admin->profile()->create(['lastname' => 'Администратор', 'firstname' => 'Администратор', 'gender' => 'male', 'email' => 'admin@admin.admin']);
        } else {
            $admin->login = 'admin';
            $admin->password = Hash::make('admin');
            $admin->save();
        }
    }
}
