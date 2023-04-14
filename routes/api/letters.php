<?php

use App\Http\Controllers\API\Letters\LettersEditController;
use App\Http\Controllers\API\Letters\LettersListController;
use Illuminate\Support\Facades\Route;

Route::post('/letters', [LettersListController::class, 'list'])->middleware('role:super');
Route::post('/letters/update', [LettersEditController::class, 'update'])->middleware('role:super');
