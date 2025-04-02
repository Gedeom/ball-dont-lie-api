<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ExternalApiTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth.or.external'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    $resources = [
        'teams' => TeamController::class,
        'players' => PlayerController::class,
        'games' => GameController::class,
    ];

    foreach($resources as $resource => $controller) {
        Route::apiResource($resource, $controller)->except('destroy');
    }

    // Routes exclusive to admin
    Route::middleware(['auth:sanctum', 'check.role:admin'])->group(function () use ($resources) {
        Route::apiResource('external-api-tokens', ExternalApiTokenController::class)->only(['store', 'update', 'destroy']);

        foreach($resources as $resource => $controller) {
            Route::apiResource($resource, $controller)->only('destroy');
        }
    });
});
