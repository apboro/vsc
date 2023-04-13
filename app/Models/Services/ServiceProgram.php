<?php

namespace App\Models\Services;

use App\Models\Dictionaries\AbstractDictionary;
use App\Models\Dictionaries\ServiceCategories;
use App\Models\Dictionaries\ServiceTypes;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $service_type_id
 * @property int $service_category_id
 * @property int $organization_id
 * @property int $name
 * @property int $order
 * @property int $enabled
 *
 * @property-read string $type
 * @property-read string $category
 *
 * @property ServiceTypes $serviceType
 * @property ServiceCategories $serviceCategory
 */
class ServiceProgram extends AbstractDictionary
{
    protected $table = 'service_programs';

    /** @var array Default attributes. */
    protected $attributes = [
        'service_type_id' => ServiceTypes::regular,
        'service_category_id' => ServiceCategories::paid,
    ];

    /** @var bool Is bound to organization */
    protected static bool $organizationBound = true;

    protected $with = ['serviceType', 'serviceCategory'];

    protected $appends = ['type', 'category'];

    /**
     * Organization this base attached to.
     *
     * @return  HasOne
     */
    public function serviceType(): HasOne
    {
        return $this->hasOne(ServiceTypes::class, 'id', 'service_type_id');
    }

    /**
     * Organization this base attached to.
     *
     * @return  HasOne
     */
    public function serviceCategory(): HasOne
    {
        return $this->hasOne(ServiceCategories::class, 'id', 'service_category_id');
    }

    public function getTypeAttribute(): string
    {
        return $this->serviceType->name;
    }

    public function getCategoryAttribute(): string
    {
        return $this->serviceCategory->name;
    }
}
