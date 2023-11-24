<?php

use App\Http\Controllers\API\Invoices\InvoicesAddController;
use App\Http\Controllers\API\Invoices\InvoicesListController;
use App\Http\Controllers\API\Invoices\InvoicesPayByAccountController;
use App\Http\Controllers\API\Invoices\InvoicesRemoveController;
use App\Http\Controllers\API\Invoices\InvoicesResendController;
use Illuminate\Support\Facades\Route;


Route::prefix('invoices')->group(function () {
    Route::post('/list', [InvoicesListController::class, 'list'])->middleware('permit:invoices.view');
    Route::post('/get', [InvoicesAddController::class, 'get'])->middleware('permit:invoices.view');
    Route::post('/save', [InvoicesAddController::class, 'update'])->middleware('permit:invoices.edit');
    Route::post('/remove', [InvoicesRemoveController::class, 'remove'])->middleware('permit:invoices.delete');
    Route::post('/pay_from_account', [InvoicesPayByAccountController::class, 'payByAccount'])->middleware('permit:invoices.edit');
    Route::post('/resend', [InvoicesResendController::class, 'resend'])->middleware('permit:invoices.edit');
});

