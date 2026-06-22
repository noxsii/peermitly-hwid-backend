<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Health\HealthCheckController;
use App\Http\Controllers\Api\LicenseKeyCheckController;
use App\Http\Controllers\Api\LicenseKeyController;
use App\Http\Controllers\Api\LicenseKeyTypeController;
use App\Http\Middleware\LogApiRequest;
use App\Support\TokenAbility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthCheckController::class, 'status'])
    ->withoutMiddleware(LogApiRequest::class)
    ->name('health');

Route::get('/user', static fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/license-keys/check', [LicenseKeyCheckController::class, 'check'])
        ->middleware(['throttle:license-key-check', 'ability:'.TokenAbility::LICENSE_KEYS_CHECK])
        ->name('license-keys.check');

    Route::middleware('ability:'.TokenAbility::LICENSE_KEYS_READ)->group(function (): void {
        Route::get('/license-keys', [LicenseKeyController::class, 'index'])->name('license-keys.index');
        Route::get('/license-keys/{licenseKey:uuid}', [LicenseKeyController::class, 'show'])->name('license-keys.show');
    });

    Route::middleware('ability:'.TokenAbility::LICENSE_KEYS_MANAGE)->group(function (): void {
        Route::post('/license-keys', [LicenseKeyController::class, 'store'])->name('license-keys.store');
        Route::delete('/license-keys/{licenseKey:uuid}', [LicenseKeyController::class, 'destroy'])->name('license-keys.destroy');
        Route::post('/license-keys/{licenseKey:uuid}/revoke', [LicenseKeyController::class, 'revoke'])->name('license-keys.revoke');
        Route::post('/license-keys/{licenseKey:uuid}/restore', [LicenseKeyController::class, 'restore'])->name('license-keys.restore');
        Route::post('/license-keys/{licenseKey:uuid}/extend', [LicenseKeyController::class, 'extend'])->name('license-keys.extend');
    });

    Route::middleware('ability:'.TokenAbility::LICENSE_KEY_TYPES_MANAGE)->group(function (): void {
        Route::get('/license-key-types', [LicenseKeyTypeController::class, 'index'])->name('license-key-types.index');
        Route::get('/license-key-types/{licenseKeyType:uuid}', [LicenseKeyTypeController::class, 'show'])->name('license-key-types.show');
        Route::post('/license-key-types', [LicenseKeyTypeController::class, 'store'])->name('license-key-types.store');
        Route::patch('/license-key-types/{licenseKeyType:uuid}', [LicenseKeyTypeController::class, 'update'])->name('license-key-types.update');
        Route::delete('/license-key-types/{licenseKeyType:uuid}', [LicenseKeyTypeController::class, 'destroy'])->name('license-key-types.destroy');
    });
});
