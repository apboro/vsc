<?php

namespace App\Models\Dictionaries;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $organization_id
 * @property int $pattern_id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 * @property PatternLetters $pattern
 */
class Letters extends AbstractDictionary
{
    /** @var int Default standard one */
    public const standard_one = 1;
    /** @var int Default standard two */
    public const standard_two = 2;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_letters';

    /** @var bool Is bound to organization */
    protected static bool $organizationBound = true;

    /**
     * Organization this clontracts attached to.
     *
     * @return  HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }

    /**
     * PatternLetters this service for.
     *
     * @return  HasOne
     */
    public function pattern(): HasOne
    {
        return $this->hasOne(PatternLetters::class, 'id', 'pattern_id');
    }
}
