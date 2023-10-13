<?php

use App\Http\Controllers\API\Clients\ClientsAddSubscriptionController;
use App\Http\Controllers\API\Clients\ClientsCommentsEditController;
use App\Http\Controllers\API\Clients\ClientsCommentsListController;
use App\Http\Controllers\API\Clients\ClientsEditController;
use App\Http\Controllers\API\Clients\ClientsListController;
use App\Http\Controllers\API\Clients\ClientsViewController;
use App\Http\Controllers\API\Clients\ClientsWardsEditController;
use App\Http\Controllers\API\Clients\ClientsWardsListController;
use Illuminate\Support\Facades\Route;

Route::post('/clients', [ClientsListController::class, 'list'])->middleware('permit:clients.view');
Route::post('/clients/get', [ClientsEditController::class, 'get'])->middleware('permit:clients.edit');
Route::post('/clients/update', [ClientsEditController::class, 'update'])->middleware('permit:clients.edit');
Route::post('/clients/view', [ClientsViewController::class, 'view'])->middleware('permit:clients.view');
Route::post('/clients/wards', [ClientsWardsListController::class, 'list'])->middleware('permit:clients.view');
Route::post('/clients/wards/get', [ClientsWardsEditController::class, 'get'])->middleware('permit:clients.edit');
Route::post('/clients/wards/update', [ClientsWardsEditController::class, 'update'])->middleware('permit:clients.edit');
Route::post('/clients/export', [ClientsListController::class, 'export'])->middleware('permit:clients.view');

Route::post('/clients/add_subscription/get', [ClientsAddSubscriptionController::class, 'get'])->middleware('permit:subscriptions.create');
Route::post('/clients/add_subscription/update', [ClientsAddSubscriptionController::class, 'update'])->middleware('permit:subscriptions.create');

Route::prefix('/clients/comments')->group(function () {
   Route::post('/', [ClientsCommentsListController::class, 'list'])->middleware('permit:client_comments.view');
   Route::post('/get', [ClientsCommentsEditController::class, 'get']);  // Permissions checked inside controller
   Route::post('/update', [ClientsCommentsEditController::class, 'update']);  // Permissions checked inside controller
   Route::post('/destroy', [ClientsCommentsEditController::class, 'destroy'])->middleware('permit:client_comments.delete');
});
