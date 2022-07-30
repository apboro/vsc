<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class TrainingBaseStatus extends AbstractDictionary
{
    /** @var int The ID of enabled status */
    public const enabled = 1;

    /** @var int The ID of disabled status */
    public const disabled = 2;

    /** @var int Default status */
    public const default = self::enabled;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_training_base_statuses';
}
