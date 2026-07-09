<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\BucketsDaily;
use Filament\Widgets\ChartWidget;
use Laravel\Sanctum\PersonalAccessToken;

final class ApiTokensChart extends ChartWidget
{
    use BucketsDaily;

    public ?string $filter = '30';

    protected static ?int $sort = 3;

    protected ?string $heading = 'API-Schlüssel';

    protected ?string $description = 'Erstellte und verwendete Schlüssel pro Tag';

    protected ?string $maxHeight = '300px';

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $options = [
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'ticks' => ['precision' => 0],
            ],
        ],
    ];

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $days = $this->filterDays($this->filter);

        return [
            'datasets' => [
                [
                    'label' => 'Erstellt',
                    'data' => $this->dailyCounts(PersonalAccessToken::query(), 'created_at', $days),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Zuletzt verwendet',
                    'data' => $this->dailyCounts(PersonalAccessToken::query(), 'last_used_at', $days),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.15)',
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $this->dayLabels($days),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getFilters(): array
    {
        return $this->dayFilters();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
