<?php

namespace Database\Seeders;

use App\Models\Dictionaries\PositionTitle;
use App\Models\Positions\Position;
use App\Models\User\User;
use App\Models\User\UserProfile;
use Exception;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     *
     * @throws Exception
     */
    public function run(): void
    {
        // foreach ($this->seeders as $seederClass) {
        //
        //     /** @var GenericSeeder $seeder */
        //     $seeder = $this->container->make($seederClass);
        //
        //     $seeder->run();
        // }

        $titles = [
            ['name' => 'Бухгалтер'],
            ['name' => 'Менеджер'],
            ['name' => 'Тренер'],
            ['name' => 'Охранник'],
        ];

        foreach ($titles as &$title) {
            $t = new PositionTitle;
            $t->name = $title['name'];
            $t->save();
            $title['id'] = $t->id;
        }
        unset($title);

        // Create users with profiles and positions
        User::factory(100)
            ->afterCreating(function (User $user) use ($titles) {
                UserProfile::factory()->create(['user_id' => $user->id]);
                Position::factory()->create(['user_id' => $user->id, 'title_id' => $titles[random_int(0, count($titles) - 1)]['id']]);
            })
            ->create();
    }
}
