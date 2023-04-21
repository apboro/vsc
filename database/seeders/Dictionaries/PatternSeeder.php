<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\Contracts;
use App\Models\Dictionaries\Letters;
use App\Models\Dictionaries\Pattern;
use App\Models\Dictionaries\PatternLetters;
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
            Pattern::anapa => [
                'name' => 'Анапа',
                'pattern' => 'pdf/contracts/subscription_golovanov_contract',
            ],
        ],
        PatternLetters::class => [
            PatternLetters::regular => [
                'name' => 'Регулярный',
                'link' => 'mail.subscriptions.link.form_link',
                'contract' => 'mail.subscriptions.contract.contract',
            ],
            PatternLetters::one_time => [
                'name' => 'Разовый',
                'link' => 'mail.subscriptions.link.form_single_link',
                'contract' => 'mail.subscriptions.contract.contract_single',
            ],
        ],
        Letters::class => [
            Letters::standard_one => [
                'name' => 'Регулярный',
                'pattern_id' => PatternLetters::regular,
                'organization_id' => 1
            ],
            Letters::standard_two => [
                'name' => 'Регулярный',
                'pattern_id' => PatternLetters::regular,
                'organization_id' => 2
            ],
        ],
        Contracts::class => [
            Contracts::standard_one => [
                'name' => 'Стандартный',
                'pattern_id' => PatternLetters::regular,
                'organization_id' => 1
            ],
            Contracts::standard_two => [
                'name' => 'Стандартный',
                'pattern_id' => PatternLetters::regular,
                'organization_id' => 2
            ],
        ],
    ];
}
