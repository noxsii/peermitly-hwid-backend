<?php

declare(strict_types=1);

namespace App\Actions\Api;

use App\Data\Api\ApiAuthResult;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final readonly class AuthenticateApiUserAction
{
    /**
     * Authenticate an API client (the Windows app) by credentials and issue a
     * Sanctum token. Sign-in requires an active account AND an active
     * subscription.
     *
     * The account is locked to the first device's hardware id (hwid). Once
     * bound, sign-in from any other device is rejected. Each successful login
     * revokes the account's previous tokens, leaving a single active session.
     *
     * @throws ValidationException
     */
    public function handle(string $email, string $password, string $deviceName, string $hwid): ApiAuthResult
    {
        $user = User::query()->where('email', $email)->first();

        if (! $user instanceof User || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        abort_unless($user->is_active, 403, 'Your account is not active.');
        abort_unless(
            $user->activeSubscription()->exists(),
            403,
            'You need an active subscription to sign in.',
        );

        if ($user->hwid === null) {
            $user->forceFill(['hwid' => $hwid])->save();
        } elseif (! hash_equals($user->hwid, $hwid)) {
            abort(403, 'This account is locked to another device.');
        }

        $user->tokens()->delete();

        $token = $user->createToken($deviceName, ['app:use'])->plainTextToken;

        return new ApiAuthResult($user, $token);
    }
}
