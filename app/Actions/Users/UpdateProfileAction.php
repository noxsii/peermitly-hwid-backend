<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;

final readonly class UpdateProfileAction
{
    public function handle(User $user, string $name): void
    {
        $user->forceFill(['name' => $name])->save();
    }
}
