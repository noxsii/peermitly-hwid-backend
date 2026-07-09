<?php

declare(strict_types=1);

namespace App\Filament\Widgets\Concerns;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait BucketsDaily
{
    /**
     * Records per day for the last $days days (oldest first), bucketed on $column.
     *
     * @param  Builder<covariant Model>  $query
     * @return list<int>
     */
    private function dailyCounts(Builder $query, string $column, int $days): array
    {
        $since = now()->subDays($days - 1)->startOfDay();
        $buckets = array_fill(0, $days, 0);

        /** @var Collection<int, CarbonInterface> $dates */
        $dates = (clone $query)
            ->whereNotNull($column)
            ->where($column, '>=', $since)
            ->pluck($column);

        foreach ($dates as $date) {
            $index = (int) $since->diffInDays($date);
            if ($index >= 0 && $index < $days) {
                $buckets[$index]++;
            }
        }

        return array_values($buckets);
    }

    /**
     * Day labels for the last $days days (oldest first), e.g. "05.07.".
     *
     * @return list<string>
     */
    private function dayLabels(int $days): array
    {
        $since = now()->subDays($days - 1)->startOfDay();

        return array_map(
            static fn (int $offset): string => $since->copy()->addDays($offset)->format('d.m.'),
            range(0, $days - 1),
        );
    }

    /**
     * The currently selected filter value as a day count.
     */
    private function filterDays(?string $filter): int
    {
        return match ($filter) {
            '7' => 7,
            '90' => 90,
            default => 30,
        };
    }

    /**
     * @return array<int, string>
     */
    private function dayFilters(): array
    {
        return [
            '7' => '7 Tage',
            '30' => '30 Tage',
            '90' => '90 Tage',
        ];
    }
}
