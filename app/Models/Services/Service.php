<?php

namespace App\Models\Services;

use App\Helpers\PriceConverter;
use App\Interfaces\Statusable;
use App\Models\Dictionaries\Contracts;
use App\Models\Dictionaries\Interfaces\AsDictionary;
use App\Models\Dictionaries\Letters;
use App\Models\Dictionaries\OrganizationRequisites;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\SportKind;
use App\Models\Dictionaries\TrainingBaseStatus;
use App\Models\Model;
use App\Models\Organization\Organization;
use App\Models\PositionService;
use App\Models\ServicePhone;
use App\Models\TrainingBase\TrainingBase;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property int|null $letter_id
 * @property string|null $description
 * @property Carbon $date_deposit_funds
 * @property float|null $advance_payment
 * @property Carbon $date_advance_payment
 * @property float|null $refund_amount
 * @property float|null $daily_price
 * @property float|null $price_deduction_advance
 * @property float|null $price
 * @property string|null $email
 *
 * @property ServiceStatus $status
 * @property Organization $organization
 * @property TrainingBase $trainingBase
 * @property SportKind $sportKind
 * @property Letters $letter
 * @property ServiceProgram $typeProgram
 * @property Contracts $contract
 * @property Collection<SportKind> $sportKinds
 * @property ServiceSchedule $schedule
 * @property OrganizationRequisites $requisites
 * @property-read Collection<PositionService> $positions
 * @property-read int|null $days_count
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
     * Convert price from store value to real price.
     *
     * @param int|null $value
     *
     * @return  float
     */
    public function getPriceAttribute(?int $value): ?float
    {
        return $value !== null ? PriceConverter::storeToPrice($value) : null;
    }

    /**
     * Convert price to store value.
     *
     * @param float|null $value
     *
     * @return  void
     */
    public function setPriceAttribute(?float $value): void
    {
        $this->attributes['price'] = $value !== null ? PriceConverter::priceToStore($value) : null;
    }

    /**
     * Convert advance payment from store value to real price.
     *
     * @param int|null $value
     *
     * @return  float
     */
    public function getAdvancePaymentAttribute(?int $value): ?float
    {
        return $value !== null ? PriceConverter::storeToPrice($value) : null;
    }

    /**
     * Convert advance payment to store value.
     *
     * @param float|null $value
     *
     * @return  void
     */
    public function setAdvancePaymentAttribute(?float $value): void
    {
        $this->attributes['advance_payment'] = $value !== null ? PriceConverter::priceToStore($value) : null;
    }

    /**
     * Convert refund amount from store value to real price.
     *
     * @param int|null $value
     *
     * @return  float
     */
    public function getRefundAmountAttribute(?int $value): ?float
    {
        return $value !== null ? PriceConverter::storeToPrice($value) : null;
    }

    /**
     * Convert refund amount to store value.
     *
     * @param float|null $value
     *
     * @return  void
     */
    public function setRefundAmountAttribute(?float $value): void
    {
        $this->attributes['refund_amount'] = $value !== null ? PriceConverter::priceToStore($value) : null;
    }

    /**
     * Convert daily price from store value to real price.
     *
     * @param int|null $value
     *
     * @return  float
     */
    public function getDailyPriceAttribute(?int $value): ?float
    {
        return $value !== null ? PriceConverter::storeToPrice($value) : null;
    }

    /**
     * Convert daily price to store value.
     *
     * @param float|null $value
     *
     * @return  void
     */
    public function setDailyPriceAttribute(?float $value): void
    {
        $this->attributes['daily_price'] = $value !== null ? PriceConverter::priceToStore($value) : null;
    }

    /**
     * Convert price deduction advance from store value to real price.
     *
     * @param int|null $value
     *
     * @return  float
     */
    public function getPriceDeductionAdvanceAttribute(?int $value): ?float
    {
        return $value !== null ? PriceConverter::storeToPrice($value) : null;
    }

    /**
     * Convert price deduction advance to store value.
     *
     * @param float|null $value
     *
     * @return  void
     */
    public function setPriceDeductionAdvanceAttribute(?float $value): void
    {
        $this->attributes['price_deduction_advance'] = $value !== null ? PriceConverter::priceToStore($value) : null;
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
     * Sports kinds relation.
     *
     * @return  BelongsToMany
     */
    public function sportKinds(): BelongsToMany
    {
        return $this->belongsToMany(SportKind::class, 'service_has_sport_kind', 'service_id', 'sport_kind_id');
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

    /**
     * Letters this service for.
     *
     * @return  HasOne
     */
    public function letter(): HasOne
    {
        return $this->hasOne(Letters::class, 'id', 'letter_id');
    }


    public function phones()
    {
        return $this->hasMany(ServicePhone::class);
    }

    public function phonesList(){
        return !empty($this->phones) ? $this->phones->pluck('phone')->implode(',') : null;
    }

    public function positions(): HasMany
    {
        return $this->hasMany(PositionService::class);
    }

    public function getDaysCountAttribute(): ?int
    {
        if (!$this->start_at || !$this->end_at) {
            return null;
        }

        return Carbon::parse($this->start_at)->diffInDays(Carbon::parse($this->end_at)) + 1;
    }
}
