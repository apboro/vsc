<?php

use App\Http\Controllers\API\Subscriptions\SubscriptionsContractAcceptController;
use App\Http\Controllers\API\Subscriptions\SubscriptionsDocumentsListController;
use App\Http\Controllers\API\Subscriptions\SubscriptionsListController;
use App\Http\Controllers\API\Subscriptions\SubscriptionsViewController;
use Illuminate\Support\Facades\Route;

Route::post('/subscriptions', [SubscriptionsListController::class, 'list'])->middleware('permit:subscriptions.view');
Route::post('/subscriptions/view', [SubscriptionsViewController::class, 'view'])->middleware('permit:subscriptions.view');
Route::post('/subscriptions/documents', [SubscriptionsDocumentsListController::class, 'list'])->middleware('permit:subscriptions.view');
Route::post('/subscriptions/documents/get', [SubscriptionsContractAcceptController::class, 'get'])->middleware('permit:subscriptions.accept.document');
Route::post('/subscriptions/documents/update', [SubscriptionsContractAcceptController::class, 'update'])->middleware('permit:subscriptions.accept.document');
