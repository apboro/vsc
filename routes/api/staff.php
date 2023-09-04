<?php

use App\Http\Controllers\API\Staff\StaffPermissionsController;
use App\Http\Controllers\API\Staff\StaffRolesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Staff\StaffListController;
use App\Http\Controllers\API\Staff\StaffViewController;
use App\Http\Controllers\API\Staff\StaffAccessController;
use App\Http\Controllers\API\Staff\StaffEditController;
use App\Http\Controllers\API\Staff\StaffDeleteController;

Route::post('/staff', [StaffListController::class, 'list'])->middleware('permit:staff.view,staff.edit,staff.delete,staff.access,staff.permissions');
Route::get('/staff/get-staff-list', [StaffListController::class, 'getStaffList'])->middleware('permit:staff.view');

Route::post('/staff/view', [StaffViewController::class, 'view'])->middleware('permit:staff.view,staff.edit,staff.delete,staff.access,staff.permissions');

Route::post('/staff/access/set', [StaffAccessController::class, 'set'])->middleware('permit:staff.access');
Route::post('/staff/access/release', [StaffAccessController::class, 'release'])->middleware('permit:staff.access');

Route::post('/staff/roles/get', [StaffRolesController::class, 'get'])->middleware('permit:staff.permissions');
Route::post('/staff/roles/update', [StaffRolesController::class, 'update'])->middleware('permit:staff.permissions');
Route::post('/staff/permissions/get', [StaffPermissionsController::class, 'get'])->middleware('permit:staff.permissions');
Route::post('/staff/permissions/update', [StaffPermissionsController::class, 'update'])->middleware('permit:staff.permissions');

Route::post('/staff/get', [StaffEditController::class, 'get'])->middleware('permit:staff.edit');
Route::post('/staff/update', [StaffEditController::class, 'update'])->middleware('permit:staff.edit');

Route::post('/staff/delete', [StaffDeleteController::class, 'delete'])->middleware('permit:staff.delete');
