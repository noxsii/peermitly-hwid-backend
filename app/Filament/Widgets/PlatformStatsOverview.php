<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\LicenseKeyStatus;
use App\Models\Customer;
use App\Models\LicenseKey;
use App\Models\Product;
use App\Models\Team;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class PlatformStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '30s';

    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        $activeKeys = LicenseKey::query()->where('status', LicenseKeyStatus::ACTIVE->value)->count();
        $totalKeys = LicenseKey::query()->count();
        $pending = LicenseKey::query()->where('status', LicenseKeyStatus::PENDING->value)->count();
        $expired = LicenseKey::query()->where('status', LicenseKeyStatus::EXPIRED->value)->count();
        $revoked = LicenseKey::query()->where('status', LicenseKeyStatus::REVOKED->value)->count();

        $createdLast14Days = LicenseKey::query()
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(created_at) AS day, COUNT(*) AS total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total')
            ->all();

        return [
            Stat::make('Active license keys', number_format($activeKeys))
                ->description(number_format($totalKeys).' total · '.number_format($pending).' pending')
                ->descriptionIcon('heroicon-o-key')
                ->color('success')
                ->chart(array_map(intval(...), $createdLast14Days)),

            Stat::make('Pending activations', number_format($pending))
                ->description('Waiting for first API check')
                ->descriptionIcon('heroicon-o-clock')
                ->color($pending > 0 ? 'warning' : 'gray'),

            Stat::make('Customers', number_format(Customer::query()->count()))
                ->description(number_format(Product::query()->count()).' products')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Teams', number_format(Team::query()->count()))
                ->description(number_format($expired).' expired · '.number_format($revoked).' revoked')
                ->descriptionIcon('heroicon-o-building-office-2')
                ->color('gray'),
        ];
    }
}
