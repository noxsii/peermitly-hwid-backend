<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AppUpdateController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Health\HealthCheckController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthCheckController::class, 'status'])->name('health');

Route::get('/update/latest', [AppUpdateController::class, 'latest'])->name('api.update.latest');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:6,1')
    ->name('login');

Route::middleware(['auth:sanctum', 'hwid'])->group(function (): void {
    Route::get('/user', static fn (Request $request) => UserResource::make($request->user())->resolve($request));

    Route::get('/subscription', [SubscriptionController::class, 'show'])
        ->name('api.subscription.status');
});
