<?php

namespace App\Models\Permissions;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $active
 * @property bool $locked
 * @property Collection $permissions
 */
class Role extends Model
{
    /** @var int Id for super-admin role */
    public const super = 1;

    /** @var array Attributes casting. */
    protected $casts = [
        'active' => 'bool',
        'locked' => 'bool',
    ];

    /** @var array Default attributes. */
    protected $attributes = [
        'locked' => false,
        'active' => true,
    ];

    /**
     * Role's permissions.
     *
     * @return  BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permission', 'role_id', 'permission_id');
    }

    /**
     * Match this role against given.
     *
     * @param int|string $role
     *
     * @return  bool
     */
    public function matches($role): bool
    {
        if (is_string($role)) {
            $role = constant('self::' . $role);
        }
        return $this->getAttribute('id') === $role;
    }

    /**
     * Get string representation.
     *
     * @return  string|null
     */
    public function asString(): ?string
    {
        return $this->id === self::super ? 'super' : null;
    }
}
