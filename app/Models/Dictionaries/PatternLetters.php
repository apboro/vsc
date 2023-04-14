<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class PatternLetters extends AbstractDictionary
{
    /** @var int Default standard one */
    public const regular = 1;
    /** @var int Default standard two */
    public const one_time = 2;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_patterns_letters';
}
