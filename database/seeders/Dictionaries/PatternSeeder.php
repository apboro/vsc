<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\Pattern;
use Database\Seeders\GenericSeeder;

class PatternSeeder extends GenericSeeder
{
    protected array $data = [
        Pattern::class => [
            Pattern::standard_one => [
                'name' => 'Стандартный',
                'pattern' => 'pdf/subscription_contract',
                'organization_id' => 1,
            ],
            Pattern::standard_two => [
                'name' => 'Стандартный',
                'pattern' => 'pdf/subscription_contract',
                'organization_id' => 2,
            ],
            Pattern::volhov => [
                'name' => 'Волхов',
                'pattern' => 'pdf/contracts/subscription_volhov_contract',
                'organization_id' => 1,
            ],
            Pattern::lukomorye => [
                'name' => 'Лукоморье',
                'pattern' => 'pdf/contracts/subscription_lukomorye_contract',
                'organization_id' => 1,
            ],
            Pattern::golovanov => [
                'name' => 'Голованов',
                'pattern' => 'pdf/contracts/subscription_golovanov_contract',
                'organization_id' => 1,
            ],
        ],
    ];
}
