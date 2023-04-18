<?php

namespace App\Models\Subscriptions;

use App\Helpers\PriceConverter;
use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int $subscription_contract_id
 *
 * @property string|null $lastname
 * @property string|null $firstname
 * @property string|null $patronymic
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $passport_serial
 * @property string|null $passport_number
 * @property string|null $passport_place
 * @property string|null $service_name
 * @property Carbon|null $passport_date
 * @property string|null $passport_code
 * @property string|null $registration_address
 * @property Carbon|null $birth_date
 *
 * @property string|null $ward_lastname
 * @property string|null $ward_firstname
 * @property string|null $ward_patronymic
 * @property Carbon|null $ward_birth_date
 * @property string|null $ward_document
 * @property Carbon|null $ward_document_date
 *
 * @property string|null $ward_passport_serial
 * @property string|null $ward_passport_number
 * @property string|null $ward_passport_place
 * @property Carbon|null $ward_passport_date
 * @property string|null $ward_passport_code
 * @property string|null $ward_registration_address
 *
 * @property string|null $organization_title
 * @property string|null $organization_inn
 * @property string|null $organization_kpp
 * @property string|null $bank_account
 * @property string|null $bank_title
 * @property string|null $bank_bik
 * @property string|null $bank_ks
 *
 * @property Carbon|null $date_advance_payment
 * @property Carbon|null $date_deposit_funds
 * @property Carbon|null $service_start_date
 * @property Carbon|null $service_end_date
 *
 * @property string|null $training_base_name
 * @property int|null $trainings_per_week
 * @property int|null $trainings_per_month
 * @property int|null $training_duration
 *
 * @property string|null $sport_kind
 * @property string|null $training_base_address
 *
 * @property float|null $monthly_price
 * @property float|null $training_return_price
 * @property float|null $price
 * @property float|null $advance_payment
 * @property float|null $refund_amount
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SubscriptionContractData extends Model
{
    /** @var string The primary key associated with the table. */
    protected $primaryKey = 'subscription_contract_id';

    /** @var bool Disable auto-incrementing on model. */
    public $incrementing = false;

    /** @var array Attribute casting */
    protected $casts = [
        'birth_date' => 'date',
        'passport_date' => 'date',
        'ward_birth_date' => 'date',
        'ward_passport_date' => 'date',
        'ward_document_date' => 'date',
        'service_start_date' => 'date',
        'service_end_date' => 'date',
        'date_advance_payment' => 'date',
        'date_deposit_funds' => 'date',
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
}
