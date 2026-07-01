<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Enums\UserRole;
use App\Models\User;

final readonly class DeactivateUnsubscribedUserAction
{
    /**
     * Deactivate a user that no longer has an active subscription. Admins are
     * never touched, and already-inactive or still-subscribed users are left
     * unchanged. Returns true only when the user was actually deactivated.
     */
    public function handle(User $user): bool
    {
        if ($user->role !== UserRole::USER) {
            return false;
        }

        if (! $user->is_active) {
            return false;
        }

        if ($user->activeSubscription()->exists()) {
            return false;
        }

        $user->update(['is_active' => false]);

        return true;
    }
}
