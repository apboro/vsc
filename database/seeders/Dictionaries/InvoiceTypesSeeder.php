<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\InvoicePaymentType;
use App\Models\Dictionaries\InvoiceType;
use Database\Seeders\GenericSeeder;

class InvoiceTypesSeeder extends GenericSeeder
{
    protected array $data = [
        InvoiceType::class => [
            InvoiceType::base => ['name' => 'Базовый счет'],
            InvoiceType::recalculation => ['name' => 'Перерасчет'],
        ],
        InvoicePaymentType::class => [
            InvoicePaymentType::acquiring => ['name' => 'Эквайринг'],
            InvoicePaymentType::cash => ['name' => 'Наличные'],
            InvoicePaymentType::bank_requisites => ['name' => 'По реквизитам в банке'],
        ],
    ];
}
