<?php

use App\Http\Controllers\API\Dictionary\DictionaryController;
use App\Http\Controllers\API\Dictionary\DictionaryDeleteController;
use App\Http\Controllers\API\Dictionary\DictionaryEditController;
use Illuminate\Support\Facades\Route;

Route::post('/dictionaries', [DictionaryController::class, 'getDictionary']);

Route::post('/dictionaries/index', [DictionaryEditController::class, 'index'])->middleware('permit:dictionaries.edit');
Route::post('/dictionaries/details', [DictionaryEditController::class, 'details'])->middleware('permit:dictionaries.edit');
Route::post('/dictionaries/sync', [DictionaryEditController::class, 'sync'])->middleware('permit:dictionaries.edit');
Route::post('/dictionaries/update', [DictionaryEditController::class, 'update'])->middleware('permit:dictionaries.edit');
Route::post('/dictionaries/delete', [DictionaryDeleteController::class, 'delete'])->middleware('permit:dictionaries.edit');
