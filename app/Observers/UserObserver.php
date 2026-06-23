<?php

declare(strict_types=1);

namespace App\Observers;

use App\Actions\Users\GenerateSecurityCodeAction;
use App\Models\User;

final readonly class UserObserver
{
    public function __construct(private GenerateSecurityCodeAction $generateSecurityCode) {}

    public function creating(User $user): void
    {
        if (blank($user->security_code)) {
            $user->security_code = $this->generateSecurityCode->handle();
        }
    }
}
