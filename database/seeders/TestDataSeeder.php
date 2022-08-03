<?php

namespace Database\Seeders;

use App\Models\Dictionaries\PositionTitle;
use App\Models\Dictionaries\SportKind;
use App\Models\Organization\Organization;
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

        $organization = new Organization();
        $organization->title = 'ООО Колобок и сыновья';
        $organization->save();
        $organization = new Organization();
        $organization->title = 'ЗАО Бармалей и Ко';
        $organization->save();

        $titles = [
            ['name' => 'Бухгалтер'],
            ['name' => 'Менеджер'],
            ['name' => 'Тренер'],
            ['name' => 'Охранник'],
        ];

        foreach ($titles as &$title) {
            $t = new PositionTitle();
            $t->name = $title['name'];
            $t->organization_id = $organization->id;
            $t->save();
            $title['id'] = $t->id;
        }
        unset($title);

        // Create users with profiles and positions
        User::factory(10)
            ->afterCreating(function (User $user) use ($titles, $organization) {
                UserProfile::factory()->create(['user_id' => $user->id]);
                Position::factory()->create(['user_id' => $user->id, 'organization_id' => $organization->id, 'title_id' => $titles[random_int(0, count($titles) - 1)]['id']]);
            })
            ->create();

        $sportKinds = [
            'Гимнастика',
            'Атлетика',
            'Футбол',
            'Хокей',
            'Дзюдо',
        ];

        foreach ($sportKinds as $sportKind) {
            $kind = new SportKind();
            $kind->name = $sportKind;
            $kind->organization_id = $organization->id;
            $kind->save();
        }
    }
}
