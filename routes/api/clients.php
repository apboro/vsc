<?php

use App\Http\Controllers\API\Clients\ClientsListController;
use App\Http\Controllers\API\Clients\ClientsViewController;
use Illuminate\Support\Facades\Route;

Route::post('/clients', [ClientsListController::class, 'list'])->middleware('permit:clients.view');
Route::post('/clients/view', [ClientsViewController::class, 'view'])->middleware('permit:clients.view');
