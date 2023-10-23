<?php

namespace App\Models\Clients;

use App\Interfaces\Statusable;
use App\Models\Dictionaries\ClientCommentType;
use App\Models\Dictionaries\ClientStatus;
use App\Models\Model;
use App\Models\Organization\Organization;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Models\User\User;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $status_id
 * @property int $organization_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ClientStatus $status
 * @property Organization $organization
 * @property User $user
 * @property Collection<ClientWard> $wards
 * @property Collection<ClientComment> $comments
 */
class Client extends Model implements Statusable
{
    use HasStatus;

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => ClientStatus::default,
    ];

    /** @var array Attribute casting */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Client status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(ClientStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for this client.
     *
     * @param int|ClientStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws InvalidArgumentException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(ClientStatus::class, $status, InvalidArgumentException::class, $save);
    }

    /**
     * Organization this client attached to.
     *
     * @return  HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }

    /**
     * Related person record.
     *
     * @return  HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Client related wards.
     *
     * @return  BelongsToMany
     */
    public function wards(): BelongsToMany
    {
        return $this->belongsToMany(ClientWard::class, 'client_has_wards', 'client_id', 'client_ward_id');
    }

    /**
     * Subscriptions of this client.
     *
     * @return  HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'client_id', 'id');
    }

    /**
     * Subscriptions of this client.
     *
     * @return  HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ClientComment::class, 'client_id', 'id');
    }

    /**
     * Create system comment
     *
     * @param string|null $text
     *
     * @param int $action
     */
    public function addComment(?string $text, int $action): void
    {
        if (empty($text)) {
            return;
        }

        $this->comments()->create([
            'text' => $text,
            'action_id' => $action,
            'type_id' => ClientCommentType::outer,
        ]);
    }

    /**
     * Update client's data in contracts
     *
     * @param array $subscriptionStatuses
     */
    public function updateContractsData(array $subscriptionStatuses)
    {
        /** @var Collection<Subscription> $subscriptions */
        $subscriptions = $this->subscriptions()
            ->with(['contracts', 'contracts.contractData'])
            ->whereIn('status_id', $subscriptionStatuses)
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            /** @var SubscriptionContract $contract */
            foreach ($subscription->contracts as $contract) {
                $contract->contractData()->update([
                    'firstname' => $this->user->profile->firstname,
                    'lastname' => $this->user->profile->lastname,
                    'patronymic' => $this->user->profile->patronymic,
                    'phone' => $this->user->profile->phone,
                    'email' => $this->user->profile->email,
                ]);
            }
        }
    }
}
