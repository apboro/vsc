<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property string $pattern
 * @property bool $enabled
 * @property int $order
 */
class Pattern extends AbstractDictionary
{
    /** @var int Default standard one */
    public const standard_one = 1;

    /** @var int Волхов */
    public const volhov = 3;

    /** @var int Лукоморье */
    public const lukomorye = 4;

    /** @var int Анапа */
    public const anapa = 5;

    public const berezovay1=6;

    public const stolichnay9=7;

    public const educational = 8;

    public const standard_ip_babayevskiy = 9;

    public const standard_ip_tkachenko = 10;


    /** @var string Referenced table name. */
    protected $table = 'dictionary_patterns';
}
