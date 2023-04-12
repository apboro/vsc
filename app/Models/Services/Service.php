<?php

namespace App\Models\Services;

use App\Helpers\PriceConverter;
use App\Interfaces\Statusable;
use App\Models\Dictionaries\Contracts;
use App\Models\Dictionaries\Interfaces\AsDictionary;
use App\Models\Dictionaries\OrganizationRequisites;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\SportKind;
use App\Models\Dictionaries\TrainingBaseStatus;
use App\Models\Model;
use App\Models\Organization\Organization;
use App\Models\TrainingBase\TrainingBase;
use App\Models\Services\ServiceProgram;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $status_id
 * @property int $organization_id
 * @property int $training_base_id
 * @property int $sport_kind_id
 * @property string $title
 * @property float|null $monthly_price
 * @property float|null $training_price
 * @property int|null $training_return_price
 * @property int|null $trainings_per_week
 * @property int|null $trainings_per_month
 * @property int|null $training_duration
 * @property int|null $group_limit
 * @property int|null $requisites_id
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $type_program_id
 * @property int|null $contract_id
 * @property string $description
 * @property Carbon $date_deposit_funds
 * @property int|null $advance_payment
 * @property Carbon $date_advance_payment
 * @property int|null $refund_amount
 * @property int|null $daily_price
 * @property int|null $price_deduction_advance
 * @property int|null $price
 *
 * @property ServiceStatus $status
 * @property Organization $organization
 * @property TrainingBase $trainingBase
 * @property SportKind $sportKind
 * @property ServiceSchedule $schedule
 * @property OrganizationRequisites $requisites
 */
class Service extends Model implements Statusable, AsDictionary
{
    use HasStatus, ServiceAsDictionary;

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => ServiceStatus::default,
    ];

    /** @var array Attribute casting */
    protected $casts = [
        'start_at' => 'datetime',
        'date_advance_payment' => 'datetime',
        'date_deposit_funds' => 'datetime',
        'end_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Convert monthly price from store value to real price.
     *
     * @param int|null $value
     *
     * @return  float
     */
    public function getMonthlyPriceAttribute(?int $value): ?float
    {
        return $value !== null ? PriceConverter::storeToPrice($value) : null;
    }

    /**
     * Convert monthly price to store value.
     *
     * @param float|null $value
     *
     * @return  void
     */
    public function setMonthlyPriceAttribute(?float $value): void
    {
        $this->attributes['monthly_price'] = $value !== null ? PriceConverter::priceToStore($value) : null;
    }

    /**
     * Convert monthly price from store value to real price.
     *
     * @param int|null $value
     *
     * @return  float
     */
    public function getTrainingPriceAttribute(?int $value): ?float
    {
        return $value !== null ? PriceConverter::storeToPrice($value) : null;
    }

    /**
     * Convert monthly price to store value.
     *
     * @param float|null $value
     *
     * @return  void
     */
    public function setTrainingPriceAttribute(?float $value): void
    {
        $this->attributes['training_price'] = $value !== null ? PriceConverter::priceToStore($value) : null;
    }

    /**
     * Convert monthly price from store value to real price.
     *
     * @param int|null $value
     *
     * @return  float
     */
    public function getTrainingReturnPriceAttribute(?int $value): ?float
    {
        return $value !== null ? PriceConverter::storeToPrice($value) : null;
    }

    /**
     * Convert monthly price to store value.
     *
     * @param float|null $value
     *
     * @return  void
     */
    public function setTrainingReturnPriceAttribute(?float $value): void
    {
        $this->attributes['training_return_price'] = $value !== null ? PriceConverter::priceToStore($value) : null;
    }

    /**
     * Service status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(ServiceStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for service.
     *
     * @param int|ServiceStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws InvalidArgumentException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(TrainingBaseStatus::class, $status, InvalidArgumentException::class, $save);
    }

    /**
     * Organization this base attached to.
     *
     * @return  HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }

    /**
     * Organization requisites for this service.
     *
     * @return  HasOne
     */
    public function requisites(): HasOne
    {
        return $this->hasOne(OrganizationRequisites::class, 'id', 'requisites_id');
    }

    /**
     * Service schedule.
     *
     * @return  HasOne
     */
    public function schedule(): HasOne
    {
        return $this->hasOne(ServiceSchedule::class, 'service_id', 'id')->withDefault();
    }

    /**
     * Training base this service for.
     *
     * @return  HasOne
     */
    public function trainingBase(): HasOne
    {
        return $this->hasOne(TrainingBase::class, 'id', 'training_base_id');
    }

    /**
     * Sport kind this service for.
     *
     * @return  HasOne
     */
    public function sportKind(): HasOne
    {
        return $this->hasOne(SportKind::class, 'id', 'sport_kind_id');
    }

    /**
     * Type program this service for.
     *
     * @return  HasOne
     */
    public function typeProgram(): HasOne
    {
        return $this->hasOne(ServiceProgram::class, 'id', 'type_program_id');
    }

    /**
     * Contract this service for.
     *
     * @return  HasOne
     */
    public function contract(): HasOne
    {
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }
}
