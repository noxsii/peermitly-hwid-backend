<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Subscription;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\PersonalAccessToken;

final class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $userTrend = $this->trend(User::query());
        $subscriptionTrend = $this->trend(Subscription::whereActive());
        $tokenTrend = $this->trend(PersonalAccessToken::query());

        return [
            Stat::make('Benutzer', User::count())
                ->description($this->weekLabel($userTrend))
                ->descriptionIcon(Heroicon::ArrowTrendingUp)
                ->chart($userTrend)
                ->color('primary'),

            Stat::make('Aktive Abonnements', Subscription::whereActive()->count())
                ->description('von '.Subscription::count().' insgesamt')
                ->descriptionIcon(Heroicon::CreditCard)
                ->chart($subscriptionTrend)
                ->color('success'),

            Stat::make('API-Schlüssel', PersonalAccessToken::count())
                ->description($this->weekLabel($tokenTrend))
                ->descriptionIcon(Heroicon::Key)
                ->chart($tokenTrend)
                ->color('info'),
        ];
    }

    /**
     * New records per day over the last 7 days, oldest first.
     *
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $query
     * @return list<float>
     */
    private function trend(Builder $query): array
    {
        $since = now()->subDays(6)->startOfDay();
        $buckets = array_fill(0, 7, 0.0);

        /** @var \Illuminate\Support\Collection<int, \Carbon\CarbonInterface> $dates */
        $dates = (clone $query)
            ->where('created_at', '>=', $since)
            ->pluck('created_at');

        foreach ($dates as $date) {
            $index = (int) $since->diffInDays($date);
            if ($index >= 0 && $index <= 6) {
                $buckets[$index]++;
            }
        }

        return $buckets;
    }

    /**
     * @param  list<float>  $trend
     */
    private function weekLabel(array $trend): string
    {
        return '+'.(int) array_sum($trend).' in den letzten 7 Tagen';
    }
}
