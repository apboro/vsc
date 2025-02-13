<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class SubscriptionContractStatus extends AbstractDictionary
{
    /** @var int The ID of active status */
    public const draft = 1;

    /** @var int The ID of contract data accepted status */
    public const accepted = 10;

    /** @var int The ID of contract data closed status */
    public const closed = 50;

    /** @var int Default status */
    public const default = self::draft;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_subscription_contract_statuses';
}
