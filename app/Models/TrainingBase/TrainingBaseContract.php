<?php

namespace App\Models\TrainingBase;

use App\Exceptions\TrainingBase\WrongTrainingBaseContractStatusException;
use App\Interfaces\Statusable;
use App\Models\Common\File;
use App\Models\Dictionaries\TrainingBaseContractStatus;
use App\Models\Model;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $status_id
 * @property int $training_base_id
 *
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property TrainingBaseContractStatus $status
 * @property Collection $files
 */
class TrainingBaseContract extends Model implements Statusable
{
    use HasStatus, HasFactory;

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => TrainingBaseContractStatus::default,
    ];

    /** @var array Attributes casting */
    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Training base contract status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(TrainingBaseContractStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for training base contract.
     *
     * @param int|TrainingBaseContractStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws WrongTrainingBaseContractStatusException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(TrainingBaseContractStatus::class, $status, WrongTrainingBaseContractStatusException::class, $save);
    }


    /**
     * Training base this contract belongs to.
     *
     * @return  BelongsTo
     */
    public function trainingBase(): BelongsTo
    {
        return $this->belongsTo(TrainingBase::class, 'training_base_id', 'id');
    }


    /**
     * Training base contract files.
     *
     * @return  BelongsToMany
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'training_base_contract_has_file', 'contract_id', 'file_id');
    }
}
