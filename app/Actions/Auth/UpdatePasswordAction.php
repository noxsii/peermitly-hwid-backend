<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;

final class UpdatePasswordAction
{
    public function handle(User $user, string $newPassword): void
    {
        $user->forceFill([
            'password' => $newPassword,
        ])->save();
    }
}
