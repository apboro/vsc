<?php

use App\Http\Controllers\API\Subscriptions\SubscriptionsListController;
use Illuminate\Support\Facades\Route;

Route::post('/subscriptions', [SubscriptionsListController::class, 'list'])->middleware('permit:subscriptions.view');
//Route::post('/leads/view', [LeadsViewController::class, 'view']);//->middleware('permit:services.view,services.edit,services.delete');
