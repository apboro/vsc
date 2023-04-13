<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class Pattern extends AbstractDictionary
{
    /** @var int Default standard one */
    public const standard_one = 1;
    /** @var int Default standard two */
    public const standard_two = 2;

    /** @var int Волхов */
    public const volhov = 3;

    /** @var int Лукоморье */
    public const lukomorye = 4;

    /** @var int Голованов */
    public const golovanov = 5;


    /** @var string Referenced table name. */
    protected $table = 'dictionary_patterns';
}
