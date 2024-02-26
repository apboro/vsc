<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class ClientOrigin extends AbstractDictionary
{
    /** @var int Почтоваая рассылка */
    public const email = 1;

    /** @var int Группа ВК */
    public const vk = 2;

    /** @var int Яндекс */
    public const yandex = 3;

    /** @var int Друзья */
    public const friends = 4;

    /** @var int Другое */
    public const other = 5;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_client_origins';
}
