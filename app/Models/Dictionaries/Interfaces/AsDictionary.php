<?php

namespace App\Models\Dictionaries\Interfaces;

use App\Current;
use Illuminate\Database\Eloquent\Builder;

interface AsDictionary
{
    /**
     * Represent model as dictionary.
     *
     * @param Current|null $current
     *
     * @return  Builder
     */
    public static function asDictionary(?Current $current = null): Builder;

    /**
     * Is bound to organization
     *
     * @return  bool
     */
    public static function isOrganizationBound(): bool;
}
