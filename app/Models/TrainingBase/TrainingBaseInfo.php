<?php

namespace App\Models\TrainingBase;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $base_id
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $description
 */
class TrainingBaseInfo extends Model
{
    use HasFactory;

    /** @var bool Disable timestamps */
    public $timestamps = false;

    /** @var string The primary key associated with the table. */
    protected $primaryKey = 'base_id';

    /** @var bool Disable auto-incrementing on model. */
    public $incrementing = false;

    /** @var string Referenced table. */
    protected $table = 'training_base_info';
}
