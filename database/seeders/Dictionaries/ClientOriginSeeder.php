<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\ClientOrigin;
use Database\Seeders\GenericSeeder;

class ClientOriginSeeder extends GenericSeeder
{
    protected array $data = [
        ClientOrigin::class => [
            ClientOrigin::email => [
                'name' => 'Почтовая рассылка',
            ],
            ClientOrigin::vk => [
                'name' => 'Группа ВК',
            ],
            ClientOrigin::yandex => [
                'name' => 'Яндекс',
            ],
            ClientOrigin::friends => [
                'name' => 'Друзья',
            ],
            ClientOrigin::other => [
                'name' => 'Другое',
            ],
        ]

    ];
}
