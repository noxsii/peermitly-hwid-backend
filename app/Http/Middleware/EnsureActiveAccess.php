<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureActiveAccess
{
    /**
     * Allow the request only when the authenticated user has full access:
     * an active account AND an active, non-expired subscription.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);
        abort_unless(
            $user->is_active && $user->activeSubscription()->exists(),
            403,
            'An active account and subscription are required.',
        );

        return $next($request);
    }
}
