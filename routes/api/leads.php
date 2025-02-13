<?php

use App\Http\Controllers\API\Leads\LeadsActionController;
use App\Http\Controllers\API\Leads\LeadsCommentController;
use App\Http\Controllers\API\Leads\LeadsListController;
use App\Http\Controllers\API\Leads\LeadsRegisterController;
use App\Http\Controllers\API\Leads\LeadsViewController;
use Illuminate\Support\Facades\Route;

Route::post('/leads', [LeadsListController::class, 'list'])->middleware('permit:leads.view,leads.register');
Route::post('/leads/services', [LeadsListController::class, 'getServicesForFilter'])->middleware('permit:leads.view,leads.register');
Route::post('/leads/view', [LeadsViewController::class, 'view'])->middleware('permit:leads.view,leads.register');
Route::post('/leads/register', [LeadsRegisterController::class, 'register'])->middleware('permit:leads.register');
Route::post('/leads/find-duplicates', [LeadsRegisterController::class, 'findDuplicates'])->middleware('permit:leads.register');
Route::post('/leads/comment', [LeadsCommentController::class, 'comment'])->middleware('permit:leads.register');
Route::post('/leads/export', [LeadsListController::class, 'export'])->middleware('permit:leads.view');
Route::post('/leads/delete', [LeadsActionController::class, 'delete'])->middleware('permit:leads.delete');
