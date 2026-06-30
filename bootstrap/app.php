<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureActiveSubscription;
use App\Http\Middleware\EnsureMatchingHwid;
use App\Http\Middleware\EnsureUserRole;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
        $middleware->alias([
            'role' => EnsureUserRole::class,
            'subscribed' => EnsureActiveSubscription::class,
            'hwid' => EnsureMatchingHwid::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $response;
            }

            $status = $response->getStatusCode();

            if (! in_array($status, [401, 403, 404, 419, 429, 500, 503], true)) {
                return $response;
            }

            return Inertia::render('errors/ErrorPage', [
                'status' => $status,
                'message' => $exception->getMessage() !== '' ? $exception->getMessage() : null,
            ])->toResponse($request)->setStatusCode($status);
        });
    })->create();
