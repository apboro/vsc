<?php

use App\Http\Controllers\API\Patterns\PatternsListController;
use Illuminate\Support\Facades\Route;

Route::post('/patterns', [PatternsListController::class, 'list'])->middleware('role:super');
