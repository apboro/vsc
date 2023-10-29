<?php

namespace App\Models\Account;

use App\Exceptions\Account\AccountException;
use App\Helpers\PriceConverter;
use App\Models\Clients\Client;
use App\Models\Dictionaries\AccountTransactionType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $client_id
 * @property int $amount
 * @property int $limit
 *
 * @property Client $client
 * @property Collection $transactions
 */
class Account extends Model
{
    /** @var array Default attributes values */
    protected $attributes = [
        'amount' => 0,
        'limit' => 0,
    ];

    /**
     * Transactions related to this account.
     *
     * @return  HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(AccountTransaction::class, 'account_id', 'id');
    }

    /**
     * The client owning this account.
     *
     * @return  BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * Convert amount to store value.
     *
     * @param float $value
     *
     * @return  void
     */
    public function setAmountAttribute(float $value): void
    {
        $this->attributes['amount'] = PriceConverter::priceToStore($value);
    }

    /**
     * Convert amount from store value to real currency.
     *
     * @param int $value
     *
     * @return  float
     */
    public function getAmountAttribute(int $value): float
    {
        return PriceConverter::storeToPrice($value);
    }

    /**
     * Convert limit to store value.
     *
     * @param float $value
     *
     * @return  void
     */
    public function setLimitAttribute(float $value): void
    {
        $this->attributes['limit'] = PriceConverter::priceToStore($value);
    }

    /**
     * Convert limit from store value to real currency.
     *
     * @param int $value
     *
     * @return  float
     */
    public function getLimitAttribute(int $value): float
    {
        return PriceConverter::storeToPrice($value);
    }

    /**
     * Create transaction for this account
     *
     * @param AccountTransaction $transaction
     * @param bool $withoutLimitCheck
     *
     * @return  AccountTransaction
     *
     * @throws AccountException
     */
    public function attachTransaction(AccountTransaction $transaction, bool $withoutLimitCheck= false): AccountTransaction
    {
        if (!$this->exists && (!$this->client_id || !$this->save())) {
            throw new AccountException('Лицевой счёт не присоединен к клиенту. Невозможно добавить операцию.');
        }
        if ($transaction->exists) {
            throw new AccountException('Повторное прикрепление операции к лицевому счёту невозможно.');
        }

        /** @var  AccountTransactionType $type */
        $type = AccountTransactionType::get($transaction->type_id);
        if (!$type->final) {
            throw new AccountException('Невозможно создать операцию с таким типом.');
        }

        if ($type->sign === -1 && ($transaction->amount > ($this->amount - ($withoutLimitCheck ? 0 : $this->limit)))) {
            throw new AccountException('Недостаточно средств на счете для совершения операции.');
        }

        $transaction->account_id = $this->id;
        $this->amount += $type->sign * $transaction->amount;

        $account = $this;

        DB::transaction(static function () use ($account, $transaction) {
            $transaction->save();
            $account->save();
        });

        return $transaction;
    }

    /**
     * Update transaction for this account
     *
     * @param AccountTransaction $transaction
     * @param array $attributes
     *
     * @return  AccountTransaction
     *
     * @throws AccountException
     */
    public function updateTransaction(AccountTransaction $transaction, array $attributes): AccountTransaction
    {
        if (!$this->exists) {
            throw new AccountException('Лицевой счёт не присоединен к клиенту. Невозможно добавить операцию.');
        }
        if (!$transaction->exists || $transaction->account_id !== $this->id) {
            throw new AccountException('Операция не привязана к лицевому счёту.');
        }

        $account = $this;
        $type = $transaction->type;

        DB::transaction(static function () use (&$account, &$transaction, $type, $attributes) {
            // update account amount
            /** @var AccountTransactionType $newType */
            $newType = isset($attributes['type_id']) ? AccountTransactionType::get($attributes['type_id']) : null;
            $account->amount -= $type->sign * $transaction->amount;
            if ($newType) {
                $account->amount += $newType->sign * ($attributes['amount'] ?? $transaction->amount);
            } else {
                $account->amount += $type->sign * ($attributes['amount'] ?? $transaction->amount);
            }
            // update transaction attributes
            foreach ($attributes as $key => $value) {
                $transaction->setAttribute($key, $value);
            }
            $transaction->save();
            $account->save();
        });

        return $transaction;
    }

    /**
     * Detach transaction from account.
     *
     * @param AccountTransaction $transaction
     *
     * @return  void
     *
     * @throws AccountException
     */
    public function detachTransaction(AccountTransaction $transaction): void
    {
        if (!$this->exists) {
            throw new AccountException('Лицевой счёт не присоединен к партнёру. Невозможно добавить операцию.');
        }
        if ($this->id !== $transaction->account_id) {
            throw new AccountException('Невозможно удалить операция из другого лицевого счёта');
        }

        $account = $this;
        $type = $transaction->type;

        DB::transaction(static function () use (&$account, &$transaction, $type) {
            $account->amount -= $type->sign * $transaction->amount;
            $account->save();
            $transaction->delete();
        });
    }

    /**
     * Recalculate amount for this account.
     *
     * @param Carbon|null $upToDate
     * @param Carbon|null $fromDate
     * @param array|null $transactionTypes
     *
     * @return  int
     */
    public function calcAmount(Carbon $upToDate = null, Carbon $fromDate = null, ?array $transactionTypes = null): int
    {
        $refill = AccountTransactionType::query()
            ->where('sign', 1)
            ->when($transactionTypes, function (Builder $query) use ($transactionTypes) {
                $query->whereIn('id', $transactionTypes);
            })
            ->pluck('id')
            ->toArray();
        $withdrawal = AccountTransactionType::query()
            ->where('sign', -1)
            ->when($transactionTypes, function (Builder $query) use ($transactionTypes) {
                $query->whereIn('id', $transactionTypes);
            })
            ->pluck('id')
            ->toArray();

        $total = $this->transactions()
            ->whereIn('type_id', $refill)
            ->when($upToDate, function (Builder $query) use ($upToDate) {
                $query->whereDate('timestamp', '<=', $upToDate);
            })
            ->when($fromDate, function (Builder $query) use ($fromDate) {
                $query->whereDate('timestamp', '>=', $fromDate);
            })
            ->sum('amount');

        $total -= $this->transactions()
            ->whereIn('type_id', $withdrawal)
            ->when($upToDate, function (Builder $query) use ($upToDate) {
                $query->whereDate('timestamp', '<=', $upToDate);
            })
            ->when($fromDate, function (Builder $query) use ($fromDate) {
                $query->whereDate('timestamp', '>=', $fromDate);
            })
            ->sum('amount');

        return PriceConverter::storeToPrice($total);
    }
}
