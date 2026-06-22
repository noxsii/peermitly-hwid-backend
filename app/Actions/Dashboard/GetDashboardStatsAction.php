<?php

declare(strict_types=1);

namespace App\Actions\Dashboard;

use App\Data\Dashboard\DashboardStats;
use App\Enums\LicenseKeyStatus;
use App\Models\ApiRequestLog;
use App\Models\Customer;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;

final readonly class GetDashboardStatsAction
{
    public function handle(int $teamId): DashboardStats
    {
        $teamKeys = LicenseKey::query()->where('team_id', $teamId);

        $active = (clone $teamKeys)->where('status', LicenseKeyStatus::ACTIVE->value)->count();
        $pending = (clone $teamKeys)->where('status', LicenseKeyStatus::PENDING->value)->count();
        $expiringSoon = (clone $teamKeys)
            ->where('status', LicenseKeyStatus::ACTIVE->value)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays(30)])
            ->count();

        $apiCalls24h = ApiRequestLog::query()
            ->where('created_at', '>=', now()->subDay())
            ->whereIn('license_key_id', (clone $teamKeys)->select('id'))
            ->count();

        return new DashboardStats(
            activeLicenseKeys: $active,
            pendingLicenseKeys: $pending,
            expiringSoon: $expiringSoon,
            customers: Customer::query()->where('team_id', $teamId)->count(),
            products: Product::query()->where('team_id', $teamId)->count(),
            licenseKeyTypes: LicenseKeyType::query()->where('team_id', $teamId)->count(),
            apiCallsLast24h: $apiCalls24h,
        );
    }
}
