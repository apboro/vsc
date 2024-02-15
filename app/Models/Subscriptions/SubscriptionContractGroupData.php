<?php

namespace App\Models\Subscriptions;

use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int $subscription_contract_id
 *
 * @property int|null $girls_1_count
 * @property int|null $boys_1_count
 * @property int|null $girls_2_count
 * @property int|null $boys_2_count
 * @property int|null $girls_3_count
 * @property int|null $boys_3_count
 * @property int|null $ward_count
 * @property int|null $trainer_count
 * @property int|null $attendant_count
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SubscriptionContractGroupData extends Model
{
    /** @var string The primary key associated with the table. */
    protected $primaryKey = 'subscription_contract_id';

    /** @var bool Disable auto-incrementing on model. */
    public $incrementing = false;

    /** @var array Attribute casting */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
