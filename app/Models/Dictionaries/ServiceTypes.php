<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class ServiceTypes extends AbstractDictionary
{
    /** @var int Регулярная */
    public const regular = 1;

    /** @var int Разовая */
    public const one_time = 2;

    /** @var int Дистанционная */
    public const remote = 3;

    /** @var int Групповая */
    public const group = 4;


    /** @var string Referenced table name. */
    protected $table = 'dictionary_service_types';
}
