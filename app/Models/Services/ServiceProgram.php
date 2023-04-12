<?php

namespace App\Models\Services;

use App\Models\Dictionaries\AbstractDictionary;
use App\Models\Dictionaries\ServiceCategories;
use App\Models\Dictionaries\ServiceTypes;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
}
