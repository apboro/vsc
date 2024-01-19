<?php

use App\Http\Controllers\API\Invoices\ExternalInvoiceController;
use App\Http\Controllers\API\Payments\PaykeeperWebhookController;
use App\Http\Controllers\API\Payments\PaymentController;
use App\Http\Controllers\API\Payments\YoukassaWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/invoice/{hash}', [ExternalInvoiceController::class, 'index'])->name('external_invoice');
Route::post('/invoice/payment', [PaymentController::class, 'makePayment'])->name('invoice.payment.make');
Route::get('/invoice/payment/success', [PaymentController::class, 'success']);
Route::get('/invoice/payment/fail', [PaymentController::class, 'fail']);
Route::post('/paykeeper/webhook', PaykeeperWebhookController::class);
Route::post('/youkassa/webhook', YoukassaWebhookController::class);
