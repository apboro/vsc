<?php

namespace App\Models;

use App\Models\Positions\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Position $position
 */
class PositionService extends Model
{
    protected $guarded = [];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id','id');
    }
}
