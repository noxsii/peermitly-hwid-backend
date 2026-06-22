<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

final class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Gate that controls who can access the Horizon dashboard.
     * Only active super admins are allowed.
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', static fn (?User $user = null): bool => $user instanceof User
            && $user->is_active
            && $user->role === UserRole::SUPER_ADMIN);
    }
}
