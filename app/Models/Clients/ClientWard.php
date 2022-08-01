<?php

namespace App\Models\Clients;

use App\Interfaces\Statusable;
use App\Models\Dictionaries\ClientWardStatus;
use App\Models\Model;
use App\Models\User\User;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $status_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ClientWardStatus $status
 * @property User $user
 * @property Collection $clients
 */
class ClientWard extends Model implements Statusable
{
    use HasStatus;

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => ClientWardStatus::default,
    ];

    /** @var array Attribute casting */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Client status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(ClientWardStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for this client.
     *
     * @param int|ClientWardStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws InvalidArgumentException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(ClientWardStatus::class, $status, InvalidArgumentException::class, $save);
    }

    /**
     * Related person record.
     *
     * @return  HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Ward related clients.
     *
     * @return  BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_has_wards', 'client_ward_id', 'client_id');
    }
}
