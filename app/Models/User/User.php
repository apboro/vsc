<?php

namespace App\Models\User;

use App\Exceptions\User\WrongUserStatusException;
use App\Interfaces\Statusable;
use App\Models\Clients\Client;
use App\Models\Dictionaries\AbstractDictionary;
use App\Models\Dictionaries\UserStatus;
use App\Models\Positions\Position;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property int $status_id
 * @property string $login
 * @property string $password
 * @property Carbon $created_at
 *
 * @property UserStatus $status
 * @property UserProfile $profile
 *
 * @property Position $position
 * @property Client $client
 */
class User extends Authenticatable implements Statusable
{
    use HasApiTokens, HasFactory, HasStatus;

    /** @var string A referenced table. */
    protected $table = 'users';

    /** @var string[] The attributes that are mass assignable. */
    protected $fillable = [
        'login',
        'password',
    ];

    /** @var array The attributes that should be hidden for serialization. */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => UserStatus::default,
    ];

    /**
     * User's status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(UserStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for user.
     *
     * @param int|AbstractDictionary $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws WrongUserStatusException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(UserStatus::class, $status, WrongUserStatusException::class, $save);
    }

    /**
     * User's profile.
     *
     * @return  HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id')->withDefault();
    }

    /**
     * Position of user.
     *
     * @return  HasOne
     */
    public function position(): HasOne
    {
        return $this->hasOne(Position::class, 'user_id', 'id');
    }

    /**
     User related client.
     *
     * @return  HasOne
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'user_id', 'id');
    }
}
