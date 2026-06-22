<?php

declare(strict_types=1);

use App\Http\Controllers\Passkey\PasskeyController;
use App\Http\Controllers\Settings\ApiTokenController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/', [SettingsController::class, 'edit'])->name('edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('/api-tokens', [ApiTokenController::class, 'store'])
        ->middleware('role:admin,super_admin')
        ->name('api-tokens.store');
    Route::delete('/api-tokens/{apiToken}', [ApiTokenController::class, 'destroy'])
        ->middleware('role:admin,super_admin')
        ->name('api-tokens.destroy');

    Route::post('/passkeys/options', [PasskeyController::class, 'options'])->name('passkeys.options');
    Route::post('/passkeys', [PasskeyController::class, 'store'])->name('passkeys.store');
    Route::delete('/passkeys/{passkey}', [PasskeyController::class, 'destroy'])->name('passkeys.destroy');
});
