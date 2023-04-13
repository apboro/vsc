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
            ],
            Pattern::volhov => [
                'name' => 'Волхов',
                'pattern' => 'pdf/contracts/subscription_volhov_contract',
            ],
            Pattern::lukomorye => [
                'name' => 'Лукоморье',
                'pattern' => 'pdf/contracts/subscription_lukomorye_contract',
            ],
            Pattern::golovanov => [
                'name' => 'Голованов',
                'pattern' => 'pdf/contracts/subscription_golovanov_contract',
            ],
        ],
    ];
}
