<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/auth.php'));

        Route::middleware('web')
            ->prefix('settings')
            ->name('settings.')
            ->group(base_path('routes/settings.php'));
    }
}
