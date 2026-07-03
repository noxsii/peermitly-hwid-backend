<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;

final readonly class VerifyEmailAction
{
    public function handle(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'is_active' => true,
        ])->save();

        event(new Verified($user));

        return true;
    }
}
