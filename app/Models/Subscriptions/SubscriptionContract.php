<?php

namespace App\Models\Subscriptions;

use App\Interfaces\Statusable;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Model;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $status_id
 * @property int $subscription_id
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property SubscriptionContractStatus $status
 * @property Subscription $subscription
 * @property SubscriptionContractData $contractData
 */
class SubscriptionContract extends Model implements Statusable
{
    use HasStatus;

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => SubscriptionContractStatus::default,
    ];

    /** @var array Attribute casting */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Subscription contract status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(SubscriptionContractStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for subscription contract.
     *
     * @param int|SubscriptionContractStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws InvalidArgumentException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(SubscriptionContractStatus::class, $status, InvalidArgumentException::class, $save);
    }

    /**
     * Subscription this contract for.
     *
     * @return  HasOne
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }

    /**
     * Subscription contract data.
     *
     * @return  HasOne
     */
    public function contractData(): HasOne
    {
        return $this->hasOne(SubscriptionContractData::class, 'subscription_contract_id', 'id')->withDefault();
    }
}
