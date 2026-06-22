<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Health\HealthCheckController;
use App\Http\Controllers\Api\UpdateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthCheckController::class, 'status'])->name('health');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:6,1')
    ->name('login');

Route::get('/user', static fn (Request $request) => $request->user())->middleware('auth:sanctum');

// Tauri auto-updater. Requires an active account + subscription, so the app
// must send its Sanctum token (Authorization: Bearer ...).
Route::middleware(['auth:sanctum', 'access'])->group(function (): void {
    Route::get('/update/download/{version}', [UpdateController::class, 'download'])
        ->name('api.update.download');

    Route::get('/update/{target}/{arch}/{current}', [UpdateController::class, 'check'])
        ->name('api.update.check');
});
