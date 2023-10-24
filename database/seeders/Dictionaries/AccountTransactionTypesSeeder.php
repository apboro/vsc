<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\AccountTransactionType;
use Database\Seeders\GenericSeeder;

class AccountTransactionTypesSeeder extends GenericSeeder
{
    /**
     * @var array|string[][][]
     *
     * Defaults:
     * 'enabled' => true
     * 'lock' => false
     */
    protected array $data = [
        AccountTransactionType::class => [
            AccountTransactionType::account_refill => [
                'name' => 'Пополнение счета',
                'sign' => 0,
                'final' => false,
                'next_title' => 'Способ пополнения',
            ],
            AccountTransactionType::account_refill_cash => [
                'name' => 'Наличными',
                'sign' => 1,
                'parent_type_id' => AccountTransactionType::account_refill,
                'final' => true,
                'has_reason' => false,
                'has_reason_date' => false,
                'editable' => true,
                'deletable' => true,
            ],
            AccountTransactionType::account_withdrawal => [
                'name' => 'Списание со счета',
                'sign' => 0,
                'final' => false,
                'next_title' => 'Способ списания',
            ],
            AccountTransactionType::account_withdrawal_cash => [
                'name' => 'Наличными',
                'sign' => -1,
                'has_reason' => true,
                'has_reason_date' => true,
                'editable' => true,
                'deletable' => true,
            ],
        ],
    ];
}
