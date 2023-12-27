<?php

namespace App\Models;

use App\Helpers\PriceConverter;
use Illuminate\Database\Eloquent\Model;

/** @property string $gate
* @property int $contract_id
* @property int|null $external_id
* @property int $amount
* @property int $status_id
*/

class Payment extends Model
{
    protected $guarded = [];

    /**
     * Convert amount to store value.
     *
     * @param float|null $value
     *
     * @return  void
     */
    public function setAmountAttribute(?float $value): void
    {
        $this->attributes['amount'] = $value !== null ? PriceConverter::priceToStore($value) : null;
    }

    /**
     * Convert amount from store value to real price.
     *
     * @param int|null $value
     *
     * @return float|null
     */
    public function getAmountAttribute(?int $value): ?float
    {
        return $value !== null ? PriceConverter::storeToPrice($value) : null;
    }
}
