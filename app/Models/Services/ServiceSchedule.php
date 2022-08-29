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
 * @property Carbon|null $mon_start_time
 * @property Carbon|null $tue_start_time
 * @property Carbon|null $wed_start_time
 * @property Carbon|null $thu_start_time
 * @property Carbon|null $fri_start_time
 * @property Carbon|null $sat_start_time
 * @property Carbon|null $sun_start_time
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
        'mon_start_time' => 'datetime:H:i',
        'tue_start_time' => 'datetime:H:i',
        'wed_start_time' => 'datetime:H:i',
        'thu_start_time' => 'datetime:H:i',
        'fri_start_time' => 'datetime:H:i',
        'sat_start_time' => 'datetime:H:i',
        'sun_start_time' => 'datetime:H:i',
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
