<?php

use App\Http\Controllers\API\TrainingBase\TrainingBaseContractDeleteController;
use App\Http\Controllers\API\TrainingBase\TrainingBaseContractEditController;
use App\Http\Controllers\API\TrainingBase\TrainingBaseContractFileController;
use App\Http\Controllers\API\TrainingBase\TrainingBaseContractListController;
use App\Http\Controllers\API\TrainingBase\TrainingBaseDeleteController;
use App\Http\Controllers\API\TrainingBase\TrainingBaseEditController;
use App\Http\Controllers\API\TrainingBase\TrainingBaseListController;
use App\Http\Controllers\API\TrainingBase\TrainingBaseViewController;
use Illuminate\Support\Facades\Route;

Route::post('/training-base', [TrainingBaseListController::class, 'list'])->middleware('permit:training_base.view,training_base.edit,training_base.delete');

Route::post('/training-base/view', [TrainingBaseViewController::class, 'view'])->middleware('permit:training_base.view,training_base.edit,training_base.delete');

Route::post('/training-base/contracts', [TrainingBaseContractListController::class, 'list'])->middleware('permit:training_base.contracts.view,training_base.contracts.modify');
Route::post('/training-base/contracts/update', [TrainingBaseContractEditController::class, 'update'])->middleware('permit:training_base.contracts.modify');
Route::post('/training-base/contracts/delete', [TrainingBaseContractDeleteController::class, 'delete'])->middleware('permit:training_base.contracts.modify');
Route::get('/training-base/contracts/files/{file}', [TrainingBaseContractFileController::class, 'get']);//->middleware('permit:permit:training_base.contracts.view,training_base.contracts.modify');

Route::post('/training-base/get', [TrainingBaseEditController::class, 'get'])->middleware('permit:training_base.view,training_base.edit');
Route::post('/training-base/update', [TrainingBaseEditController::class, 'update'])->middleware('permit:training_base.view,training_base.edit');

Route::post('/training-base/delete', [TrainingBaseDeleteController::class, 'delete'])->middleware('permit:training_base.delete');
