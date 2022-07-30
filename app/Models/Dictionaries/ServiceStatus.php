<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class ServiceStatus extends AbstractDictionary
{
    /** @var int The ID of active status */
    public const enabled = 1;

    /** @var int The ID of blocked status */
    public const disabled = 2;

    /** @var int Default status */
    public const default = self::enabled;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_service_statuses';
}
