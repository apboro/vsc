<?php

use App\Http\Controllers\API\Contracts\ContractsEditController;
use App\Http\Controllers\API\Contracts\ContractsListController;
use App\Http\Controllers\API\Letters\LettersEditController;
use App\Http\Controllers\API\Letters\LettersListController;
use App\Http\Controllers\API\Organizations\OrganizationDeleteController;
use App\Http\Controllers\API\Organizations\OrganizationEditController;
use App\Http\Controllers\API\Organizations\OrganizationListController;
use App\Http\Controllers\API\Organizations\OrganizationViewController;
use App\Http\Controllers\API\Organizations\SwitchOrganizationController;
use Illuminate\Support\Facades\Route;

Route::post('/organizations', [OrganizationListController::class, 'list'])->middleware('role:super');
Route::post('/organizations/view', [OrganizationViewController::class, 'view'])->middleware('role:super');
Route::post('/organizations/get', [OrganizationEditController::class, 'get'])->middleware('role:super');
Route::post('/organizations/update', [OrganizationEditController::class, 'update'])->middleware('role:super');
Route::post('/organizations/delete', [OrganizationDeleteController::class, 'delete'])->middleware('role:super');

Route::post('/organizations/list', [SwitchOrganizationController::class, 'list'])->middleware('role:super');
Route::post('/organizations/switch', [SwitchOrganizationController::class, 'switch'])->middleware('role:super');

Route::post('/organizations/contracts', [ContractsListController::class, 'list'])->middleware('role:super');
Route::post('/organizations/contracts/update', [ContractsEditController::class, 'update'])->middleware('role:super');

Route::post('/organizations/letters', [LettersListController::class, 'list'])->middleware('role:super');
Route::post('/organizations/letters/update', [LettersEditController::class, 'update'])->middleware('role:super');
