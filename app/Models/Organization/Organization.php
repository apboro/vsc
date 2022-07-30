<?php

namespace App\Models\Organization;

use App\Exceptions\Organization\WrongOrganizationStatusException;
use App\Interfaces\Statusable;
use App\Models\Dictionaries\OrganizationStatus;
use App\Models\Model;
use App\Models\Positions\Position;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $status_id
 * @property string $title
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property OrganizationStatus $status
 * @property OrganizationInfo $info
 *
 * @property Position $position
 */
class Organization extends Model implements Statusable
{
    use HasStatus;

    /** @var array Attribute casting. */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** @var string[] The attributes that are mass assignable. */
    protected $fillable = [
        'title',
    ];

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => OrganizationStatus::default,
    ];

    /**
     * Organization status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(OrganizationStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for user.
     *
     * @param int|OrganizationStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws WrongOrganizationStatusException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(OrganizationStatus::class, $status, WrongOrganizationStatusException::class, $save);
    }

    /**
     * Organization info
     *
     * @return  HasOne
     */
    public function info(): HasOne
    {
        return $this->hasOne(OrganizationInfo::class, 'organization_id', 'id')->withDefault();
    }
}
