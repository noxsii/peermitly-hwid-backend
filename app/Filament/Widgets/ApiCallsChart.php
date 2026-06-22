<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\ApiRequestLog;
use DateTimeInterface;
use Filament\Widgets\ChartWidget;

final class ApiCallsChart extends ChartWidget
{
    protected ?string $heading = 'API calls (last 24 hours)';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $start = now()->subHours(23)->startOfHour();

        $rows = ApiRequestLog::query()
            ->where('created_at', '>=', $start)
            ->selectRaw("DATE_TRUNC('hour', created_at) AS bucket, COUNT(*) AS total, SUM(CASE WHEN status >= 400 THEN 1 ELSE 0 END) AS errors")
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();

        $totals = [];
        $errors = [];
        foreach ($rows as $row) {
            $bucket = $row->getAttribute('bucket');
            if ($bucket instanceof DateTimeInterface) {
                $key = $bucket->format('Y-m-d H:00:00');
            } elseif (is_scalar($bucket)) {
                $key = (string) $bucket;
            } else {
                continue;
            }

            $total = $row->getAttribute('total');
            $errorCount = $row->getAttribute('errors');

            $totals[$key] = is_numeric($total) ? (int) $total : 0;
            $errors[$key] = is_numeric($errorCount) ? (int) $errorCount : 0;
        }

        $labels = [];
        $okData = [];
        $errData = [];

        for ($i = 0; $i < 24; $i++) {
            $hour = $start->copy()->addHours($i);
            $key = $hour->format('Y-m-d H:00:00');
            $labels[] = $hour->format('H:00');
            $total = $totals[$key] ?? 0;
            $err = $errors[$key] ?? 0;
            $okData[] = max(0, $total - $err);
            $errData[] = $err;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Success',
                    'data' => $okData,
                    'backgroundColor' => '#22c55e',
                    'stack' => 'calls',
                ],
                [
                    'label' => 'Errors',
                    'data' => $errData,
                    'backgroundColor' => '#ef4444',
                    'stack' => 'calls',
                ],
            ],
            'labels' => $labels,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['stacked' => true],
                'y' => ['stacked' => true, 'beginAtZero' => true],
            ],
        ];
    }
}
