<?php

use App\Http\Controllers\Leads\LeadContractController;
use App\Http\Controllers\Leads\LeadInfoController;
use App\Http\Controllers\Leads\LeadNewController;
use App\Http\Controllers\Leads\LeadFormController;
use App\Http\Controllers\Leads\LeadInitController;
use App\Http\Controllers\LeadsGroup\LeadGroupContractController;
use App\Http\Controllers\LeadsGroup\LeadGroupFormController;
use App\Http\Controllers\LeadsGroup\LeadGroupInfoController;
use App\Http\Controllers\LeadsGroup\LeadGroupInitController;
use App\Http\Controllers\LeadsGroup\LeadGroupNewController;
use App\Http\Controllers\LeadsSingle\LeadSingleContractController;
use App\Http\Controllers\LeadsSingle\LeadSingleFormController;
use App\Http\Controllers\LeadsSingle\LeadSingleInfoController;
use App\Http\Controllers\LeadsSingle\LeadSingleInitController;
use App\Http\Controllers\LeadsSingle\LeadSingleNewController;
use Illuminate\Support\Facades\Route;

Route::get('/leads/form', [LeadFormController::class, 'index'])->name('leads.subscription.contract.fill');
Route::get('/leads-single/form', [LeadSingleFormController::class, 'index'])->name('leads_single.subscription.contract.fill');
Route::get('/leads-group/form', [LeadGroupFormController::class, 'index'])->name('leads_group.subscription.contract.fill');

Route::middleware(['leads'])->group(function () {
    Route::post('/leads/init', [LeadInitController::class, 'init']);
    Route::post('/leads/info', [LeadInfoController::class, 'info'])->middleware('leads.protect');
    Route::post('/leads/send', [LeadNewController::class, 'send'])->middleware('leads.protect');
    Route::post('/leads/contract', [LeadContractController::class, 'contract'])->middleware('leads.protect');
    Route::get('/leads/contract/{subscriptionKey}', [LeadContractController::class, 'preview']);

    Route::post('/leads-single/init', [LeadSingleInitController::class, 'init']);
    Route::post('/leads-single/info', [LeadSingleInfoController::class, 'info'])->middleware('leads.protect');
    Route::post('/leads-single/send', [LeadSingleNewController::class, 'send'])->middleware('leads.protect');
    Route::post('/leads-single/contract', [LeadSingleContractController::class, 'contract'])->middleware('leads.protect');
    Route::get('/leads-single/contract/{subscriptionKey}', [LeadSingleContractController::class, 'preview']);

    Route::post('leads-group/init', [LeadGroupInitController::class, 'init']);
    Route::post('/leads-group/info', [LeadGroupInfoController::class, 'info'])->middleware('leads.protect');
    Route::post('/leads-group/send', [LeadGroupNewController::class, 'send'])->middleware('leads.protect');
    Route::post('/leads-group/contract', [LeadGroupContractController::class, 'contract'])->middleware('leads.protect');
    Route::get('/leads-group/contract/{subscriptionKey}', [LeadGroupContractController::class, 'preview']);
});
