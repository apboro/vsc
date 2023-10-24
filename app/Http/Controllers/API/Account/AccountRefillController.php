<?php

namespace App\Http\Controllers\API\Account;

use App\Current;
use App\Exceptions\Account\AccountException;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Account\AccountTransaction;
use App\Models\Clients\Client;
use App\Models\Dictionaries\AccountTransactionType;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountRefillController extends ApiEditController
{
    protected array $rules = [
        'type_id' => 'required',
        'amount' => 'required|numeric|min:1',
        'timestamp' => 'required',
    ];

    protected array $titles = [
        'type_id' => 'Допустимый остаток по счёту',
        'amount' => 'Допустимый остаток по счёту',
        'timestamp' => 'Дата операции',
    ];

    /**
     *
     * @param Request $request
     *
     * @return  JsonResponse
     *
     * @throws AccountException
     */
    public function refill(Request $request): JsonResponse
    {
        $current = Current::get($request);

        $clientId = $request->input('client_id');

        $transactionId = $request->input('transactionId');

        if (
            ($transactionId === null && !$current->can('account_transactions.create'))
            ||
            ($transactionId !== null && !$current->can('account_transactions.edit'))
        ) {
            return APIResponse::forbidden();
        }

        /** @var Client $client */
        if ($clientId === null || null === ($client = Client::query()->where('id', $clientId)->first())) {
            return APIResponse::notFound('Клиент не найден');
        }

        /** @var AccountTransaction $transaction */
        $transaction = AccountTransaction::query()->where('id', $transactionId)->first();

        $data = $this->getData($request);

        /** @var AccountTransactionType $type */
        $type = AccountTransactionType::get($data['type_id']);

        if (!$type->editable || ($transaction && !$transaction->type->editable)) {
            return APIResponse::error('Этот тип операций нельзя редактировать.');
        }

        if ($type->has_reason) {
            $this->rules['reason'] = 'required';
            $this->titles['reason'] = $type->reason_title;
        }
        if ($type->has_reason_date) {
            $this->rules['reason_date'] = 'required';
            $this->titles['reason_date'] = $type->reason_date_title;
        }

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        $timestamp = Carbon::parse($data['timestamp']);
        if ($timestamp->isToday()) {
            $now = Carbon::now();
            $timestamp->hours($now->hour);
            $timestamp->minutes($now->minute);
        }

        if (!$transaction) {
            $client->account->attachTransaction(new AccountTransaction([
                'type_id' => $data['type_id'],
                'timestamp' => $timestamp,
                'amount' => $data['amount'],
                'reason' => $type->has_reason ? $data['reason'] : null,
                'reason_date' => $type->has_reason_date ? $data['reason_date'] : null,
                'committer_id' => Current::get($request)->positionId(),
                'comments' => $data['comments'],
            ]));
        } else {
            $client->account->updateTransaction($transaction, [
                'type_id' => $data['type_id'],
                'timestamp' => $timestamp,
                'amount' => $data['amount'],
                'reason' => $type->has_reason ? $data['reason'] : null,
                'reason_date' => $type->has_reason_date ? $data['reason_date'] : null,
                'committer_id' => Current::get($request)->positionId(),
                'comments' => $data['comments'],
            ]);
        }

        return APIResponse::success('Данные обновлены', [
            'id' => $client->id,
        ]);
    }
}
