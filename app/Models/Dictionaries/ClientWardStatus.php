<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class ClientWardStatus extends AbstractDictionary
{
    /** @var int The ID of active status */
    public const active = 1;

    /** @var int Default status */
    public const default = self::active;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_client_ward_statuses';
}
