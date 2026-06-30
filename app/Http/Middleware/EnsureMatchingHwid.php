<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureMatchingHwid
{
    /**
     * Reject authenticated requests whose `X-HWID` header does not match the
     * hardware id the account is bound to. Stops a stolen or shared token from
     * being replayed on a different machine.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        if ($user->hwid !== null) {
            $sent = (string) $request->header('X-HWID');

            abort_unless(hash_equals($user->hwid, $sent), 403, 'Device mismatch.');
        }

        return $next($request);
    }
}
