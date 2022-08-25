<?php

namespace App\Models\Services;

use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $service_id
 * @property string $text
 *
 * @property boolean $mon
 * @property boolean $tue
 * @property boolean $wed
 * @property boolean $thu
 * @property boolean $fri
 * @property boolean $sat
 * @property boolean $sun
 * @property Carbon|null $start_time
 *
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

    /** @var array Attribute casting. */
    protected $casts = [
        'mon' => 'bool',
        'tue' => 'bool',
        'wed' => 'bool',
        'thu' => 'bool',
        'fri' => 'bool',
        'sat' => 'bool',
        'sun' => 'bool',
        'start_time' => 'datetime:H:i',
    ];

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
