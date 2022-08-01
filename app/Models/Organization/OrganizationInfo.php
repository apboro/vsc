<?php

namespace App\Models\Organization;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Organization $organization
 */
class OrganizationInfo extends Model
{
    /** @var string Referenced table */
    protected $table = 'organization_info';

    /** @var string The primary key associated with the table. */
    protected $primaryKey = 'organization_id';

    /** @var bool Disable auto-incrementing on model. */
    public $incrementing = false;

    /**
     * User this profile belongs to.
     *
     * @return  BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
