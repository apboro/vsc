<?php

namespace App\Models\Leads;

use App\Interfaces\Statusable;
use App\Models\Clients\Client;
use App\Models\Dictionaries\LeadStatus;
use App\Models\Model;
use App\Models\Organization\Organization;
use App\Models\Services\Service;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $status_id
 * @property int $organization_id
 * @property string|null $lastname
 * @property string|null $firstname
 * @property string|null $patronymic
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $service_id
 * @property int|null $client_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property LeadStatus $status
 * @property Organization $organization
 * @property Service|null $service
 * @property Client|null $client
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
     * Client of this lead.
     *
     * @return  HasOne
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}
