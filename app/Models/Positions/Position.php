<?php

namespace App\Models\Positions;

use App\Current;
use App\Exceptions\Positions\WrongPositionStatusException;
use App\Interfaces\Statusable;
use App\Models\Dictionaries\Interfaces\AsDictionary;
use App\Models\Dictionaries\PositionStatus;
use App\Models\Dictionaries\PositionTitle;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Model;
use App\Models\Organization\Organization;
use App\Models\Permissions\Permission;
use App\Models\Permissions\Role;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int|null $organization_id
 * @property int $user_id
 * @property int $status_id
 * @property int $title_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property PositionTitle|null $title
 * @property Organization|null $organization
 * @property PositionStatus $status
 * @property User $user
 * @property PositionInfo $info
 * @property Collection $roles
 */
class Position extends Model implements Statusable, AsDictionary
{
    use HasStatus, HasFactory;

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => PositionStatus::default,
    ];

    /** @var string[] Fillable attributes. */
    protected $fillable = [
        'title',
    ];

    /** @var array|null Position permissions cache. */
    protected ?array $permissionsCache = null;

    /**
     * Position's status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(PositionStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for position.
     *
     * @param int|PositionStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws WrongPositionStatusException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(PositionStatus::class, $status, WrongPositionStatusException::class, $save);
    }

    /**
     * Role's permissions.
     *
     * @return  BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'position_has_role', 'position_id', 'role_id')->where('active', true);
    }

    /**
     * Check if position has role.
     *
     * @param int|string $checking
     * @param bool $fresh
     *
     * @return  bool
     */
    public function hasRole($checking, bool $fresh = false): bool
    {
        if ($fresh && $this->relationLoaded('roles')) {
            $this->unsetRelation('roles');
        }

        $this->loadMissing('roles');

        foreach ($this->getRelation('roles') as $role) {
            /** @var Role $role */
            if ($role->matches($checking)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Role's permissions.
     *
     * @return  BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'position_has_permission', 'position_id', 'permission_id');
    }

    /**
     * Check position permission.
     *
     * @param string|null $key
     * @param bool $fresh
     *
     * @return  bool
     */
    public function can(?string $key, bool $fresh = false): bool
    {
        if (empty($key)) {
            return true;
        }

        if ($this->roles()->whereIn('id', [Role::super])->count() > 0) {
            return true;
        }

        return in_array($key, $this->getPermissionsList($fresh), true);
    }

    /**
     * Get assigned permissions list.
     *
     * @param bool $fresh
     *
     * @return  array
     */
    public function getPermissionsList(bool $fresh = false): array
    {
        if ($this->permissionsCache === null || $fresh) {
            $this->permissionsCache = [];

            if ($this->roles()->whereIn('id', [Role::super])->count() > 0) {
                $permissions = Permission::query()->get();
            } else {
                $roles = $this->roles()->with('permissions')->get();
                foreach ($roles as $role) {
                    /** @var Role $role */
                    foreach ($role->permissions as $permission) {
                        /** @var Permission $permission */
                        $this->permissionsCache[$permission->id] = $permission->key;
                    }
                }
                $permissions = $this->permissions()->get();
            }
            foreach ($permissions as $permission) {
                /** @var Permission $permission */
                $this->permissionsCache[$permission->id] = $permission->key;
            }
        }

        return $this->permissionsCache;
    }

    /**
     * Position's title.
     *
     * @return  HasOne
     */
    public function title(): HasOne
    {
        return $this->hasOne(PositionTitle::class, 'id', 'title_id');
    }

    /**
     * Position related user.
     *
     * @return  HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Position's organization.
     *
     * @return  HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }

    /**
     * Position info.
     *
     * @return  HasOne
     */
    public function info(): HasOne
    {
        return $this->hasOne(PositionInfo::class, 'position_id', 'id')->withDefault();
    }


    public static function asDictionary(?Current $current = null): Builder
    {
        $query = Position::query()->tap(new ForOrganization($current ? $current->organizationId() : null))
                                ->leftJoin('users', 'users.id', '=', 'positions.user_id')
                                ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                                ->select('user_profiles.firstname as order')
                                ->addSelect(DB::raw('CONCAT(user_profiles.lastname," ",user_profiles.firstname," ",user_profiles.patronymic) as name'))
                                 ->addSelect('positions.*');
        return $query;
    }

    /**
     * Is bound to organization
     *
     * @return  bool
     */
    public static function isOrganizationBound(): bool
    {
        return true;
    }
}
