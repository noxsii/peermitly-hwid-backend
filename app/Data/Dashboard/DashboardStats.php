<?php

declare(strict_types=1);

namespace App\Data\Dashboard;

final readonly class DashboardStats
{
    public function __construct(
        public int $totalUsers,
        public int $activeUsers,
        public int $verifiedUsers,
        public int $newUsersLast7Days,
    ) {}

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'total_users' => $this->totalUsers,
            'active_users' => $this->activeUsers,
            'verified_users' => $this->verifiedUsers,
            'new_users_last_7_days' => $this->newUsersLast7Days,
        ];
    }
}
