<?php

namespace App\Http\Controllers\API\Dictionary;

use App\Models\Dictionaries\Discount;
use App\Models\Dictionaries\OrganizationRequisites;
use App\Models\Dictionaries\PositionTitle;
use App\Models\Dictionaries\Region;
use App\Models\Dictionaries\SportKind;
use App\Models\TypesPrograms\TypeProgram;

trait EditableDictionaries
{
    protected array $dictionaries = [
        'position_titles' => [
            'name' => 'Должности',
            'class' => PositionTitle::class,
            'item_name' => 'должность',
            'validation' => ['name' => 'required'],
            'titles' => ['name' => 'Должность'],
            'fields' => ['name' => 'string'],
        ],
        'sport_kinds' => [
            'name' => 'Виды спорта',
            'class' => SportKind::class,
            'item_name' => 'вид спорта',
            'validation' => ['name' => 'required'],
            'titles' => ['name' => 'Вид спорта'],
            'fields' => ['name' => 'string'],
        ],
        'regions' => [
            'name' => 'Районы',
            'class' => Region::class,
            'item_name' => 'район',
            'validation' => ['name' => 'required'],
            'titles' => ['name' => 'Район'],
            'fields' => ['name' => 'string'],
        ],
        'organization_requisites' => [
            'name' => 'Реквизиты',
            'class' => OrganizationRequisites::class,
            'item_name' => 'реквизиты',
            'validation' => [
                'name' => 'required',
                'organization_title' => 'required',
                'organization_inn' => 'required',
                'organization_kpp' => 'required',
                'bank_account' => 'required',
                'bank_title' => 'required',
                'bank_bik' => 'required',
                'bank_ks' => 'required',
            ],
            'titles' => [
                'name' => 'Название',
                'organization_title' => 'Наименование организации',
                'organization_inn' => 'ИНН',
                'organization_kpp' => 'КПП',
                'bank_account' => 'Р/с',
                'bank_title' => 'Банк',
                'bank_bik' => 'БИК',
                'bank_ks' => 'К/с',
            ],
            'fields' => [
                'name' => 'string',
                'organization_title' => 'string',
                'organization_inn' => 'string',
                'organization_kpp' => 'string',
                'bank_account' => 'string',
                'bank_title' => 'string',
                'bank_bik' => 'string',
                'bank_ks' => 'string',
            ],
            'hide' => [
                'organization_title',
                'organization_inn',
                'organization_kpp',
                'bank_account',
                'bank_bik',
                'bank_ks',
            ],
        ],
        'discounts' => [
            'name' => 'Льготы',
            'class' => Discount::class,
            'item_name' => 'льготу',
            'validation' => [
                'name' => 'required',
                'discount' => 'required',
                'description' => 'required',
            ],
            'titles' => [
                'name' => 'Наименование',
                'discount' => 'Скидка, %',
                'description' => 'Описание',
            ],
            'fields' => [
                'name' => 'string',
                'discount' => 'number',
                'description' => 'text',
            ],
            'auto' => 'description',
        ],
        'types_programs' => [
            'name' => 'Типы программ',
            'class' => TypeProgram::class,
            'item_name' => 'тип программы',
            'validation' => [
                'name' => 'required',
                'service_type_id' => 'required',
                'service_category_id' => 'required',
            ],
            'titles' => [
                'name' => 'Название',
                'service_type_id' => 'Вид услуги',
                'service_category_id' => 'Категория услуги',
            ],
            'fields' => [
                'name' => 'string',
                'service_type_id' => 'select',
                'service_category_id' => 'select',
            ],
        ],
    ];
}
