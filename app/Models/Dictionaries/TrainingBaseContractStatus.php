<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class TrainingBaseContractStatus extends AbstractDictionary
{
    /** @var int The ID of active status */
    public const active = 1;

    /** @var int The ID of inactive status */
    public const inactive = 2;

    /** @var int Default status */
    public const default = self::active;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_training_base_contract_statuses';
}
