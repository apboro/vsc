<?php

namespace App\Models\TrainingBase;

use App\Exceptions\TrainingBase\WrongTrainingBaseStatusException;
use App\Interfaces\Statusable;
use App\Models\Common\Image;
use App\Models\Dictionaries\Interfaces\AsDictionary;
use App\Models\Dictionaries\Region;
use App\Models\Dictionaries\SportKind;
use App\Models\Dictionaries\TrainingBaseStatus;
use App\Models\Model;
use App\Models\Organization\Organization;
use App\Models\Services\Service;
use App\Traits\HasStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $status_id
 * @property int $organization_id
 * @property string $title
 * @property string|null $short_title
 * @property int|null $region_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property TrainingBaseStatus $status
 * @property TrainingBaseInfo $info
 * @property Collection $images
 * @property Collection $sportKinds
 * @property Collection $contracts
 * @property Organization $organization
 * @property Region $region
 * @property Collection $services
 */
class TrainingBase extends Model implements Statusable, AsDictionary
{
    use HasStatus, TrainingBaseAsDictionary;

    /** @var array Default attributes. */
    protected $attributes = [
        'status_id' => TrainingBaseStatus::default,
    ];

    /**
     * Training base status.
     *
     * @return  HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(TrainingBaseStatus::class, 'id', 'status_id');
    }

    /**
     * Check and set new status for training base.
     *
     * @param int|TrainingBaseStatus $status
     * @param bool $save
     *
     * @return  void
     *
     * @throws WrongTrainingBaseStatusException
     */
    public function setStatus($status, bool $save = true): void
    {
        $this->checkAndSetStatus(TrainingBaseStatus::class, $status, WrongTrainingBaseStatusException::class, $save);
    }

    /**
     * Organization this base attached to.
     *
     * @return  HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }

    /**
     * Region of this training base.
     *
     * @return  HasOne
     */
    public function region(): HasOne
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }

    /**
     * Training base info.
     *
     * @return  HasOne
     */
    public function info(): HasOne
    {
        return $this->hasOne(TrainingBaseInfo::class, 'base_id', 'id')->withDefault();
    }

    /**
     * Training base images.
     *
     * @return  BelongsToMany
     */
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'training_base_has_image', 'training_base_id', 'image_id');
    }

    /**
     * Training base sport kinds.
     *
     * @return  BelongsToMany
     */
    public function sportKinds(): BelongsToMany
    {
        return $this->belongsToMany(SportKind::class, 'training_base_has_sport_kinds', 'training_base_id', 'sport_kind_id');
    }

    /**
     * Training base contracts.
     *
     * @return  HasMany
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(TrainingBaseContract::class, 'training_base_id', 'id');
    }

    /**
     * Services of this training base.
     *
     * @return  HasMany
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'training_base_id', 'id');
    }
}
