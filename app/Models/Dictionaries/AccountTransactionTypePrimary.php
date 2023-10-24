<?php

namespace App\Models\Dictionaries;

use App\Current;
use App\Models\Dictionaries\Interfaces\AsDictionary;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 * @property int $sign
 */
class AccountTransactionTypePrimary extends AccountTransactionType implements AsDictionary
{
    /**
     * Make dictionary query.
     *
     * @param Current|null $current
     *
     * @return  Builder
     */
    public static function asDictionary(?Current $current = null): Builder
    {
        return self::query()->whereNull('parent_type_id');
    }
}
