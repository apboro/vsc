<?php

namespace App\Http\Controllers\API\Dictionary;

use App\Models\Dictionaries\Discount;
use App\Models\Dictionaries\OrganizationRequisites;
use App\Models\Dictionaries\PositionTitle;
use App\Models\Dictionaries\Region;
use App\Models\Dictionaries\SportKind;
use App\Models\Services\ServiceProgram;

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
                'header_of_contract'=>'required',
                'organization_inn' => 'required',
                'organization_kpp' => 'nullable',
                'organization_ogrn' => 'nullable',
                'bank_account' => 'required',
                'bank_title' => 'required',
                'bank_bik' => 'required',
                'bank_ks' => 'required',
                'email'=>'sometimes|email',
                'legal_address'=>'nullable',
                'web_site'=>'nullable',
                'phone'=>'nullable',
                'sign'=>'required',
            ],
            'titles' => [
                'name' => 'Название',
                'organization_title' => 'Наименование организации',
                'header_of_contract'=>'Шапка договора',
                'organization_inn' => 'ИНН',
                'organization_kpp' => 'КПП',
                'bank_account' => 'Р/с',
                'bank_title' => 'Банк',
                'bank_bik' => 'БИК',
                'bank_ks' => 'К/с',
                'organization_ogrn'=>'ОГРН',
                'email'=>'Email',
                'legal_address'=>'Юр.адрес',
                'web_site'=>'Страница в сети интернет',
                'phone'=>'Контактный телефон',
                'sign'=>'Расшифровка подписи в договоре',
            ],
            'fields' => [
                'name' => 'string',
                'organization_title' => 'string',
                'header_of_contract'=>'string',
                'organization_inn' => 'string',
                'organization_kpp' => 'string',
                'organization_ogrn'=>'string',
                'bank_account' => 'string',
                'bank_title' => 'string',
                'bank_bik' => 'string',
                'bank_ks' => 'string',
                'email'=>'string',
                'phone'=>'string',
                'web_site'=>'string',
                'legal_address'=>'string',
                'sign'=>'string',
            ],
            'hide' => [
                'organization_title',
                'header_of_contract',
                'organization_inn',
                'organization_kpp',
                'bank_account',
                'bank_bik',
                'bank_ks',
                'email',
                'legal_address',
                'organization_ogrn',
                'web_site',
                'phone',
                'sign',
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
        'service_programs' => [
            'name' => 'Типы программ',
            'class' => ServiceProgram::class,
            'item_name' => 'тип программы',
            'validation' => [
                'name' => 'required',
                'service_type_id' => 'required',
                'service_category_id' => 'required',
            ],
            'titles' => [
                'name' => 'Название',
                'type' => 'Вид услуги',
                'category' => 'Категория услуги',
                'service_type_id' => 'Вид услуги',
                'service_category_id' => 'Категория услуги',
            ],
            'fields' => [
                'name' => 'string',
                'type' => null,
                'category' => null,
                'service_type_id' => 'dictionary|service_types|Выберите вид услуги',
                'service_category_id' => 'dictionary|service_categories|Выберите категорию услуги',
            ],
            'hide' => ['service_type_id', 'service_category_id'],
        ],
    ];
}
