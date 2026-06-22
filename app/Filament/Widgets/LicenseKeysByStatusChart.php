<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\LicenseKeyStatus;
use App\Models\LicenseKey;
use Filament\Widgets\ChartWidget;

final class LicenseKeysByStatusChart extends ChartWidget
{
    protected ?string $heading = 'License keys by status';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'doughnut';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $counts = LicenseKey::query()
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $labels = [];
        $data = [];

        foreach (LicenseKeyStatus::cases() as $case) {
            $labels[] = ucfirst($case->value);
            $data[] = (int) ($counts[$case->value] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Keys',
                    'data' => $data,
                    'backgroundColor' => [
                        '#a78bfa', // pending - violet
                        '#22c55e', // active - green
                        '#f59e0b', // expired - amber
                        '#ef4444', // revoked - red
                        '#64748b', // blocked - slate
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }
}
