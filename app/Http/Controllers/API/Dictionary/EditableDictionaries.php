<?php

namespace App\Http\Controllers\API\Dictionary;

use App\Models\Dictionaries\PositionTitle;

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
    ];
}
