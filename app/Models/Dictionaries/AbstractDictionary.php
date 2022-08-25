<?php

namespace App\Models\Dictionaries;

use App\Current;
use App\Models\Model;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $name
 */
abstract class AbstractDictionary extends Model
{
    /** @var array Attributes casting. */
    protected $casts = [
        'enabled' => 'boolean',
        'lock' => 'boolean',
        'order' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** @var bool Is bound to organization */
    protected static bool $organizationBound = false;

    /**
     * Get dictionary item instance by an ID.
     *
     * @param int $id
     *
     * @return  Model|null
     */
    public static function get(int $id): ?Model
    {
        /** @var Model $model */
        $model = self::query()->where('id', $id)->first();

        return $model ?? null;
    }

    public static function isOrganizationBound(): bool
    {
        return property_exists(static::class, 'organizationBound') ? static::$organizationBound : false;
    }

    /**
     * Begin querying the model.
     *
     * @param Current|null $current
     *
     * @return Builder
     */
    public static function query(?Current $current = null): Builder
    {
        if (method_exists(static::class, 'isOrganizationBound') && self::isOrganizationBound()) {
            return (new static())->newQuery()->tap(new ForOrganization($current ? $current->organizationId() : null));
        }

        return (new static())->newQuery();
    }

    /**
     * Begin querying the model.
     *
     * @return Builder
     */
    public static function queryRaw(): Builder
    {
        return (new static())->newQuery();
    }
}
