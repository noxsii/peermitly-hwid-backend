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
     * @throws ValidationException
     */
    public function handle(string $email, string $password, string $deviceName): ApiAuthResult
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

        $token = $user->createToken($deviceName, ['spoofer:use'])->plainTextToken;

        return new ApiAuthResult($user, $token);
    }
}
