<?php

declare(strict_types=1);

namespace App\Actions\Dashboard;

use App\Data\Dashboard\DashboardStats;
use App\Models\User;

final readonly class GetDashboardStatsAction
{
    public function handle(): DashboardStats
    {
        return new DashboardStats(
            totalUsers: User::query()->count(),
            activeUsers: User::query()->where('is_active', true)->count(),
            verifiedUsers: User::query()->whereNotNull('email_verified_at')->count(),
            newUsersLast7Days: User::query()->where('created_at', '>=', now()->subDays(7))->count(),
        );
    }
}
