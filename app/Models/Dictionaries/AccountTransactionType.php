<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 * @property int $sign
 * @property int $parent_type_id
 * @property bool $final
 * @property string $next_title
 * @property bool $has_reason
 * @property string $reason_title
 * @property bool $has_reason_date
 * @property string $reason_date_title
 * @property bool $editable
 * @property bool $deletable
 *
 * @property AccountTransactionType $parent
 */
class AccountTransactionType extends AbstractDictionary
{
    /** @var int The id of partner account refill. Not final. */
    public const account_refill = 1;

    /** @var int The id of partner account refill by cash. Final. */
    public const account_refill_cash = 2;

    /** @var int The id of partner account withdrawal. Not final. */
    public const account_withdrawal = 3;

    /** @var int The id of partner account withdrawal by cash. Final. */
    public const account_withdrawal_cash = 4;


    /** @var string Referenced table name. */
    protected $table = 'dictionary_account_transaction_types';

    /** @var string[] Attributes casting. */
    protected $casts = [
        'editable' => 'bool',
        'deletable' => 'bool',
    ];

    /**
     * Name attribute mutator.
     *
     * @param $value
     *
     * @return  string|null
     */
    public function getNameAttribute($value): ?string
    {
        if ($this->parent_type_id !== null) {
            return $this->parent->name . ' (' . mb_strtolower($value) . ')';
        }

        return $value;
    }

    /**
     * Related parent type.
     *
     * @return  HasOne
     */
    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_type_id');
    }
}
