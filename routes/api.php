<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::post('/token', [ApiTokenController::class, 'token']);
Route::apiResource('/teams', TeamController::class)->except('destroy');
Route::apiResource('/games', GameController::class)->only(['index', 'store', 'show']);
