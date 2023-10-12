<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\ClientCommentActionType;
use App\Models\Dictionaries\ClientCommentType;
use App\Models\Dictionaries\Contracts;
use App\Models\Dictionaries\Letters;
use App\Models\Dictionaries\Pattern;
use App\Models\Dictionaries\PatternLetters;
use Database\Seeders\GenericSeeder;

class ClientCommentsSeeder extends GenericSeeder
{
    protected array $data = [
        ClientCommentType::class => [
            ClientCommentType::inner => [
                'name' => 'Внутренний',
            ],
            ClientCommentType::outer => [
                'name' => 'Внешний',
            ],
        ],
        ClientCommentActionType::class => [
            ClientCommentActionType::close_contract => [
                'name' => 'Закрытие договора',
            ],
            ClientCommentActionType::change_subscription => [
                'name' => 'Замена подписки',
            ],
            ClientCommentActionType::add_subscription => [
                'name' => 'Добавление подписки',
            ],
            ClientCommentActionType::lead_card => [
                'name' => 'Карточка лида',
            ],
            ClientCommentActionType::lead_convert => [
                'name' => 'Конвертация лида',
            ],
        ],
    ];
}
