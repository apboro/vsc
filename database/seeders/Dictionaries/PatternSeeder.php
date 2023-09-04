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
                'name' => 'Лен обл',
                'pattern' => 'pdf/subscription_contract',
            ],
            Pattern::volhov => [
                'name' => 'Карелия',
                'pattern' => 'pdf/contracts/subscription_volhov_contract',
            ],
            Pattern::lukomorye => [
                'name' => 'Псковская область',
                'pattern' => 'pdf/contracts/subscription_lukomorye_contract',
            ],
            Pattern::anapa => [
                'name' => 'Анапа',
                'pattern' => 'pdf/contracts/subscription_golovanov_contract',
            ],
            Pattern::berezovay1 => [
                'name' => 'Березовая 1',
                'pattern' => 'pdf/contracts/subscription_berezovay1_contract',
            ],
            Pattern::stolichnay9 => [
                'name' => 'Столичная 9',
                'pattern' => 'pdf/contracts/subscription_stolichnay9_contract',
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
    ];
}
