<?php

declare(strict_types=1);

use App\Http\Controllers\Team\TeamController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin,super_admin'])->group(function (): void {
    Route::get('/', [TeamController::class, 'index'])->name('index');
    Route::patch('/{team:uuid}', [TeamController::class, 'update'])->name('update');
});
