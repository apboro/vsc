<?php

use App\Http\Controllers\API\Account\AccountLimitController;
use App\Http\Controllers\API\Account\AccountRefillController;
use App\Http\Controllers\API\Account\AccountWithdrawalController;
use App\Http\Controllers\API\Account\TransactionDeleteController;
use App\Http\Controllers\API\Account\TransactionsListController;
use Illuminate\Support\Facades\Route;

Route::post('/account', [TransactionsListController::class, 'list'])->middleware('permit:account_transactions.view');
//Route::post('/account/limit', [AccountLimitController::class, 'setAccountLimit'])->middleware('allow:staff_admin,staff_office_manager,staff_accountant' );
Route::post('/account/refill', [AccountRefillController::class, 'refill']);  // ability is checked inside controller
Route::post('/account/withdrawal', [AccountWithdrawalController::class, 'withdrawal']);  // ability is checked inside controller
Route::post('/account/delete', [TransactionDeleteController::class, 'delete'])->middleware('permit:account_transactions.delete');

