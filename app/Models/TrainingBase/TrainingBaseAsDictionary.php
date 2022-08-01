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
            ->select([
                'id',
                'short_title as name',
                DB::raw('IF(status_id = ' . TrainingBaseStatus::enabled . ', true, false) as enabled'),
                'short_title as order',
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
