<?php

declare(strict_types=1);

use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PrivacyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'show'])->name('home');
Route::get('/privacy', [PrivacyController::class, 'show'])->name('privacy');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/changelog', [ChangelogController::class, 'index'])->name('changelog.index');

    // Downloads require an active account AND an active subscription.
    Route::middleware('access')->group(function (): void {
        Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');
        Route::get('/downloads/{release:uuid}', [DownloadController::class, 'download'])->name('downloads.download');
    });

    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});
