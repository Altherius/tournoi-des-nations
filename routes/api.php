<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\EloHistoryEntryController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/games', GameController::class)->only(['index', 'store', 'show']);
Route::apiResource('/teams', TeamController::class)->except('destroy');
Route::get('/teams/{team}/games', [TeamController::class, 'games']);
Route::get('/teams/{team}/elo-history', [EloHistoryEntryController::class, 'team']);
Route::post('/token', [ApiTokenController::class, 'token']);
Route::apiResource('/tournaments', TournamentController::class)->except('destroy');
Route::get('/tournaments/{tournament}/games', [TournamentController::class, 'games']);
