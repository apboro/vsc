<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\Bank;
use Database\Seeders\GenericSeeder;

class BanksSeeder extends GenericSeeder
{
    protected array $data = [
        Bank::class => [
            Bank::SBERBANK => ['name' => 'СБЕРБАНК', 'full_name' => 'ПАО Сбербанк'],
            Bank::TINKOFF => ['name' => 'ТИНЬКОФФ', 'full_name' => 'ПАО Тинькофф'],
            Bank::TOCHKA => ['name' => 'ТОЧКА', 'full_name' => 'ПАО Точка'],
        ],
    ];
}
