<?php

namespace App\Models\Dictionaries;

use App\Helpers\PriceConverter;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 * @property int $organization_id
 *
 * @property float $discount
 * @property string|null $description
 */
class Discount extends AbstractDictionary
{
    /** @var string Referenced table name. */
    protected $table = 'dictionary_discounts';

    /** @var bool Is bound to organization */
    protected static bool $organizationBound = true;

    /**
     * Convert monthly price from store value to real price.
     *
     * @param int|null $value
     *
     * @return  float
     */
    public function getDiscountAttribute(?int $value): ?float
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
    public function setDiscountAttribute(?float $value): void
    {
        $this->attributes['discount'] = $value !== null ? PriceConverter::priceToStore($value) : null;
    }
}
