<?php

namespace App\Http\Controllers\API\Dictionary;

use App\Models\Dictionaries\PositionTitle;
use App\Models\Dictionaries\SportKind;

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
    ];
}
