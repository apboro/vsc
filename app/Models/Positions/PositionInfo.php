<?php

namespace App\Models\Positions;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $position_id
 * @property string $work_phone
 * @property string $work_phone_additional
 * @property string $notes
 */
class PositionInfo extends Model
{
    use HasFactory;

    /** @var string The primary key associated with the table. */
    protected $primaryKey = 'position_id';

    /** @var bool Disable auto-incrementing on model. */
    public $incrementing = false;

    /** @var string Referenced table. */
    protected $table = 'position_info';
}
