<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\ServiceCategories;
use App\Models\Dictionaries\ServiceTypes;
use Database\Seeders\GenericSeeder;

class ServiceSeeder extends GenericSeeder
{
    protected array $data = [
        ServiceTypes::class => [
            ServiceTypes::regular => [
                'name' => 'Регулярная',
            ],
            ServiceTypes::one_time => [
                'name' => 'Разовая',
            ],
            ServiceTypes::remote => [
                'name' => 'Дистанционная',
            ],
            ServiceTypes::group => [
                'name' => 'Групповая',
            ],
        ],

        ServiceCategories::class => [
            ServiceCategories::paid => [
                'name' => 'Платная',
            ],
            ServiceCategories::free => [
                'name' => 'Бесплатная',
            ],
        ],
    ];
}
