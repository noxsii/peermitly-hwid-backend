<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Spatie\LaravelPasskeys\Models\Passkey;

final class PasskeyPolicy
{
    public function delete(User $user, Passkey $passkey): bool
    {
        return (int) $passkey->getAttribute('authenticatable_id') === $user->id;
    }
}
