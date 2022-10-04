<?php

namespace App\Models\TrainingBase;

use App\Current;
use App\Models\Dictionaries\TrainingBaseStatus;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait TrainingBaseAsDictionary
{
    /**
     * Represent model as dictionary.
     *
     * @param Current|null $current
     *
     * @return  Builder
     */
    public static function asDictionary(?Current $current = null): Builder
    {
        return self::query()
            ->tap(new ForOrganization($current ? $current->organizationId() : null))
            ->leftJoin('training_base_info', 'training_base_info.base_id', '=', 'training_bases.id')
            ->select([
                'training_bases.id',
                DB::raw('CONCAT_WS(\' \', IFNULL(training_bases.short_title, training_bases.title), CONCAT(\'(\', training_base_info.address, \')\')) as name'),
                DB::raw('IF(status_id = ' . TrainingBaseStatus::enabled . ', true, false) as enabled'),
                'short_title as order',
                'region_id',
                'address',
                'created_at',
                'updated_at',
            ]);
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
