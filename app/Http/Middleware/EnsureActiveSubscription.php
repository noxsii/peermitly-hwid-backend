<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureActiveSubscription
{
    /**
     * Block the request unless the authenticated user has an active,
     * non-expired subscription.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);
        abort_unless($user->activeSubscription()->exists(), 403, 'No active subscription.');

        return $next($request);
    }
}
