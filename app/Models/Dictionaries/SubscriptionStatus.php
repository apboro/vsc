<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class SubscriptionStatus extends AbstractDictionary
{
    /** @var int The ID of new subscription status */
    public const new = 1;

    /** @var int The ID of subscription contract fill repeat status */
    public const fill = 3;

    /** @var int The ID of subscription with filled contract draft status */
    public const filled = 10;

    /** @var int Default status */
    public const default = self::new;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_subscription_statuses';
}
