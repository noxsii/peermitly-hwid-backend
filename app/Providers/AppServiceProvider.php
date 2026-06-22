<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Policies\PasskeyPolicy;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Spatie\LaravelPasskeys\Models\Passkey;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $scramble = Scramble::configure();
        $scramble->routes(fn (Route $route) => Str::startsWith($route->uri, 'api/'));
        $scramble->withDocumentTransformers(function (OpenApi $openApi): void {
            $scheme = SecurityScheme::http('bearer');
            if ($scheme instanceof SecurityScheme) {
                $openApi->secure($scheme);
            }
        });

        Gate::define('viewApiDocs', static fn (User $user): bool => $user->is_active && ($user->role === 'admin' || $user->role === 'super_admin'));

        Gate::policy(Passkey::class, PasskeyPolicy::class);

        RateLimiter::for('license-key-check', static fn (Request $request) => Limit::perMinute(60)->by(
            $request->user()?->id !== null
                ? 'user:'.$request->user()->id.':'.($request->ip() ?? 'unknown')
                : 'ip:'.($request->ip() ?? 'unknown'),
        ));
    }
}
