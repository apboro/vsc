<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\Contracts;
use App\Models\Dictionaries\Pattern;
use Database\Seeders\GenericSeeder;

class ContractSeeder extends GenericSeeder
{
    protected array $data = [
        Contracts::class => [
            Contracts::standard_one => [
                'name' => 'Стандартный шаблон',
                'pattern_id' => Pattern::standard_one,
                'organization_id' => 1,
            ],
            Contracts::standard_two => [
                'name' => 'Стандартный шаблон',
                'pattern_id' => Pattern::standard_two,
                'organization_id' => 2,
            ],
        ],
    ];
}
