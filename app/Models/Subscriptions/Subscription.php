<?php

namespace App\Models\Subscriptions;

use App\Interfaces\Statusable;
use App\Models\Clients\Client;
use App\Models\Clients\ClientWard;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Leads\Lead;
use App\Models\Model;
use App\Models\Organization\Organization;
use App\Models\Services\Service;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $status_id
 * @property int $organization_id
 *
 * @property int $client_id
 * @property int $client_ward_id
 * @property int $service_id
 *
 * @property SubscriptionStatus $status
 * @property Organization $organization
 * @property Client $client
 * @property ClientWard $clientWard
 * @property Service $service
 * @property-read Collection<SubscriptionContract> $contracts
 * @property-read Collection<SubscriptionContract> $contractsActive
 * @property Lead|null $lead
 */
class Subscription extends Model implements Statusable
{
    use HasStatus;

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => SubscriptionStatus::default,
    ];

    /** @var array Attribute casting */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Subscription status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(SubscriptionStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for subscription.
     *
     * @param int|SubscriptionStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws InvalidArgumentException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(SubscriptionStatus::class, $status, InvalidArgumentException::class, $save);
    }

    /**
     * Organization this subscription attached to.
     *
     * @return  HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }

    /**
     * Service this subscription for.
     *
     * @return  HasOne
     */
    public function service(): HasOne
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    /**
     * Client subscribed.
     *
     * @return  HasOne
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    /**
     * Client ward attached to this subscribed.
     *
     * @return  HasOne
     */
    public function clientWard(): HasOne
    {
        return $this->hasOne(ClientWard::class, 'id', 'client_ward_id');
    }

    /**
     * Subscription contracts.
     *
     * @return  HasMany
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(SubscriptionContract::class, 'subscription_id', 'id');
    }

    /**
     * Subscription contracts.
     *
     * @return  HasMany
     */
    public function contractsActive(): HasMany
    {
        return $this->contracts()->active();
    }

    /**
     * Subscription lead.
     *
     * @return  HasOne
     */
    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class, 'subscription_id', 'id');
    }
}
