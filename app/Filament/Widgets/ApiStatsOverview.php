<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\ApiRequestLog;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class ApiStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 6;

    protected ?string $pollingInterval = '30s';

    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        $since24h = now()->subDay();
        $since1h = now()->subHour();

        $total24h = ApiRequestLog::query()->where('created_at', '>=', $since24h)->count();
        $errors24h = ApiRequestLog::query()->where('created_at', '>=', $since24h)->where('status', '>=', 400)->count();
        $last1h = ApiRequestLog::query()->where('created_at', '>=', $since1h)->count();
        $avgMs = (int) ApiRequestLog::query()->where('created_at', '>=', $since24h)->avg('duration_ms');
        $errorRate = $total24h > 0 ? round(($errors24h / $total24h) * 100, 1) : 0.0;

        return [
            Stat::make('API calls · 24h', number_format($total24h))
                ->description(number_format($last1h).' in the last hour')
                ->descriptionIcon('heroicon-o-signal')
                ->color('primary'),

            Stat::make('Errors · 24h', number_format($errors24h))
                ->description($errorRate.'% error rate')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($errorRate >= 5 ? 'danger' : ($errorRate >= 1 ? 'warning' : 'success')),

            Stat::make('Avg latency', $avgMs.' ms')
                ->description('Average over 24 hours')
                ->descriptionIcon('heroicon-o-bolt')
                ->color($avgMs >= 1000 ? 'danger' : ($avgMs >= 300 ? 'warning' : 'success')),
        ];
    }
}
