<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserRole
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $allowed = array_values(array_filter(array_map(
            UserRole::tryFrom(...),
            $roles,
        )));

        abort_unless($allowed !== [] && in_array($user->role, $allowed, true), 403);

        return $next($request);
    }
}
