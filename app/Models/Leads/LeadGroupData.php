<?php

namespace App\Models\Leads;

use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $lead_id
 *
 * @property string|null $organization_name
 * @property bool $is_contract_individual
 * @property bool $is_contract_legal
 *
 * @property int|null $girls_1_count
 * @property int|null $boys_1_count
 * @property int|null $girls_2_count
 * @property int|null $boys_2_count
 * @property int|null $girls_3_count
 * @property int|null $boys_3_count
 * @property int $ward_count
 * @property int $trainer_count
 * @property int $attendant_count
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Lead $lead
 */
class LeadGroupData extends Model
{
    /** @var array Attribute casting */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }

    public function getIsContractLegalAttribute(): bool
    {
        return !$this->is_contract_individual;
    }

    public function setIsContractLegalAttribute(bool $value)
    {
        $this->is_contract_individual = !$value;
    }
}
