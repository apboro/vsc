<?php

namespace App\Models\Subscriptions;

use App\Interfaces\Statusable;
use App\Models\Dictionaries\Discount;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Invoices\Invoice;
use App\Models\Model;
use App\Scopes\ForOrganization;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $status_id
 * @property int $subscription_id
 * @property int|null $discount_id
 * @property int|null $number
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property Carbon|null $closed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property SubscriptionContractStatus $status
 * @property Subscription $subscription
 * @property SubscriptionContractData $contractData
 * @property Discount|null $discount
 * @property-read Collection<Invoice> $invoices
 *
 * @method static Illuminate\Database\Eloquent\Builder active()
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
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Calculate amount for base type invoice
     * @return int
     */
    public function calculateBaseInvoiceTotal(): int
    {
        $monthlyPrice = $this->subscription->service->monthly_price;
        $discount = $this->discount;

        return ceil($monthlyPrice * (1 - ($discount ? $discount->discount / 10000 : 0)));
    }

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
     * Get new contract number.
     *
     * @param int $organizationId
     *
     * @return  int
     */
    public static function getNewNumber(int $organizationId): int
    {
        $last = self::query()
            ->whereHas('subscription', function (Builder $query) use ($organizationId) {
                $query->tap(new ForOrganization($organizationId));
            })
            ->max('number');

        return $last === null ? 1 : $last + 1;
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
     * Discount for this subscription this contract.
     *
     * @return  HasOne
     */
    public function discount(): HasOne
    {
        return $this->hasOne(Discount::class, 'id', 'discount_id');
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

    public static function scopeActive(Builder $q)
    {
        $today = Carbon::today();

        $q->where('status_id', SubscriptionContractStatus::accepted)
            ->whereDate('start_at','<=', $today)
            ->whereDate('end_at','>=', $today);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'contract_id', 'id');
    }
}
