<?php

use App\Http\Controllers\API\Contracts\ContractsEditController;
use App\Http\Controllers\API\Contracts\ContractsListController;
use Illuminate\Support\Facades\Route;

Route::post('/contracts', [ContractsListController::class, 'list'])->middleware('role:super');
Route::post('/contracts/get', [ContractsEditController::class, 'get'])->middleware('role:super');
Route::post('/contracts/update', [ContractsEditController::class, 'update'])->middleware('role:super');
