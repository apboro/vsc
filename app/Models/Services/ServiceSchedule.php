<?php

namespace App\Models\Services;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $service_id
 * @property string $text
 * @property Service $service
 */
class ServiceSchedule extends Model
{
    use HasFactory;

    /** @var string The primary key associated with the table. */
    protected $primaryKey = 'service_id';

    /** @var bool Disable auto-incrementing on model. */
    public $incrementing = false;

    /** @var string A referenced table. */
    protected $table = 'service_schedules';

    /**
     * Service this schedule belongs to.
     *
     * @return  BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
