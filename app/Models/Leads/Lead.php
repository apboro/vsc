<?php

namespace App\Models\Leads;

use App\Interfaces\Statusable;
use App\Models\Dictionaries\LeadStatus;
use App\Models\Dictionaries\Region;
use App\Models\Model;
use App\Models\Organization\Organization;
use App\Models\Services\Service;
use App\Models\Subscriptions\Subscription;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $status_id
 * @property int $organization_id
 *
 * @property string|null $lastname
 * @property string|null $firstname
 * @property string|null $patronymic
 * @property string|null $email
 * @property string|null $phone
 *
 * @property string|null $ward_lastname
 * @property string|null $ward_firstname
 * @property string|null $ward_patronymic
 * @property Carbon|null $ward_birth_date
 *
 * @property bool $ward_inv
 * @property bool $ward_hro
 * @property bool $ward_uch
 * @property bool $ward_spe
 *
 * @property int|null $region_id
 * @property int|null $service_id
 * @property bool $need_help
 *
 * @property string|null $client_comments
 * @property string|null $comments
 *
 * @property int|null $subscription_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property LeadStatus $status
 * @property Organization $organization
 * @property Region|null $region
 * @property Service|null $service
 * @property Subscription|null $subscription
 * @property Carbon $converted_at
 */
class Lead extends Model implements Statusable
{
    use HasStatus;

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => LeadStatus::default,
    ];

    /** @var array Attribute casting */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'ward_birth_date' => 'date',
        'ward_inv' => 'bool',
        'ward_hro' => 'bool',
        'ward_uch' => 'bool',
        'ward_spe' => 'bool',
        'need_help' => 'bool',
        'converted_at' => 'datetime',
    ];

    /**
     * Lead status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(LeadStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for this lead.
     *
     * @param int|LeadStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws InvalidArgumentException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(LeadStatus::class, $status, InvalidArgumentException::class, $save);
    }

    /**
     * Organization this lead attached to.
     *
     * @return  HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }

    /**
     * Service this lead for.
     *
     * @return  HasOne
     */
    public function service(): HasOne
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    /**
     * Region this lead assigned to.
     *
     * @return  HasOne
     */
    public function region(): HasOne
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }

    /**
     * Subscription created from this lead (with client and ward).
     *
     * @return  HasOne
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }

}
