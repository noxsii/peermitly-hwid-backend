<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Health\CheckSystemHealthAction;
use App\Contracts\SystemHealthChecker;
use Illuminate\Support\ServiceProvider;

final class SystemHealthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SystemHealthChecker::class, CheckSystemHealthAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
