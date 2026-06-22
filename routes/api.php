<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Health\HealthCheckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthCheckController::class, 'status'])->name('health');

Route::get('/user', static fn (Request $request) => $request->user())->middleware('auth:sanctum');
