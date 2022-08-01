<?php

use App\Http\Controllers\API\Services\ServicesDeleteController;
use App\Http\Controllers\API\Services\ServicesEditController;
use App\Http\Controllers\API\Services\ServicesListController;
use App\Http\Controllers\API\Services\ServicesViewController;
use Illuminate\Support\Facades\Route;

Route::post('/services', [ServicesListController::class, 'list'])->middleware('permit:services.view,services.edit,services.delete');
Route::post('/services/view', [ServicesViewController::class, 'view'])->middleware('permit:services.view');
Route::post('/services/get', [ServicesEditController::class, 'get'])->middleware('permit:services.edit');
Route::post('/services/update', [ServicesEditController::class, 'update'])->middleware('permit:services.edit');
Route::post('/services/delete', [ServicesDeleteController::class, 'delete'])->middleware('permit:services.delete');
