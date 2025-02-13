<?php

use App\Http\Controllers\API\Subscriptions\SubscriptionContractController;
use App\Http\Controllers\API\Subscriptions\SubscriptionContractViewController;
use App\Http\Controllers\API\Subscriptions\SubscriptionsChangeController;
use App\Http\Controllers\API\Subscriptions\SubscriptionsContractAcceptController;
use App\Http\Controllers\API\Subscriptions\SubscriptionsController;
use App\Http\Controllers\API\Subscriptions\SubscriptionsDocumentsListController;
use App\Http\Controllers\API\Subscriptions\SubscriptionsListController;
use App\Http\Controllers\API\Subscriptions\SubscriptionsViewController;
use Illuminate\Support\Facades\Route;

Route::post('/subscriptions', [SubscriptionsListController::class, 'list'])->middleware('permit:subscriptions.view');
Route::post('/subscriptions/export', [SubscriptionsListController::class, 'export'])->middleware('permit:subscriptions.view');
Route::post('/subscriptions/view', [SubscriptionsViewController::class, 'view'])->middleware('permit:subscriptions.view');
Route::post('/subscriptions/close', [SubscriptionsController::class, 'close'])->middleware('permit:subscriptions.close');
Route::post('/subscriptions/change/get', [SubscriptionsChangeController::class, 'get'])->middleware('permit:subscriptions.change');
Route::post('/subscriptions/change/update', [SubscriptionsChangeController::class, 'update'])->middleware('permit:subscriptions.change');

Route::post('/subscriptions/documents', [SubscriptionsDocumentsListController::class, 'list'])->middleware('permit:subscriptions.view');
Route::post('/subscriptions/documents/get', [SubscriptionsContractAcceptController::class, 'get'])->middleware('permit:subscriptions.accept.document');
Route::post('/subscriptions/documents/update', [SubscriptionsContractAcceptController::class, 'update'])->middleware('permit:subscriptions.accept.document');
Route::post('/subscriptions/documents/update_client_data', [SubscriptionsContractAcceptController::class, 'updateClientData'])->middleware('permit:subscriptions.accept.document');
Route::post('/subscriptions/documents/update_contract_data', [SubscriptionsContractAcceptController::class, 'updateContractData'])->middleware('permit:subscriptions.accept.document');

Route::post('/subscriptions/documents/resend', [SubscriptionContractController::class, 'resend'])->middleware('permit:subscriptions.send.document');
Route::post('/subscriptions/documents/close', [SubscriptionContractController::class, 'close'])->middleware('permit:subscriptions.close.document');
Route::post('/subscriptions/documents/send_link', [SubscriptionContractController::class, 'sendLink'])->middleware('permit:subscriptions.create.document');

Route::get('/subscriptions/documents/view/{id}', [SubscriptionContractViewController::class, 'view'])->middleware('permit:subscriptions.view');
Route::post('/subscriptions/documents/download', [SubscriptionContractViewController::class, 'download'])->middleware('permit:subscriptions.view');
