<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\LicenseKey;
use Filament\Widgets\ChartWidget;

final class LicenseKeysCreatedChart extends ChartWidget
{
    protected ?string $heading = 'License keys created (last 30 days)';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $start = now()->subDays(29)->startOfDay();

        $counts = LicenseKey::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) AS day, COUNT(*) AS total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->all();

        $labels = [];
        $data = [];

        for ($i = 0; $i < 30; $i++) {
            $day = $start->copy()->addDays($i);
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('M j');
            $data[] = (int) ($counts[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Keys created',
                    'data' => $data,
                    'borderColor' => '#a78bfa',
                    'backgroundColor' => 'rgba(167, 139, 250, 0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
