<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\SubscriptionPlan;
use App\Filament\Widgets\Concerns\BucketsDaily;
use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

final class SubscriptionsChart extends ChartWidget
{
    use BucketsDaily;

    public ?string $filter = '30';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Abonnements';

    protected ?string $description = 'Neue Abonnements pro Tag';

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
                    'label' => 'Neue Abonnements',
                    'data' => $this->dailyCounts(Subscription::query(), 'created_at', $days),
                    'borderColor' => '#f97316',
                    'backgroundColor' => 'rgba(249, 115, 22, 0.15)',
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'davon Pro',
                    'data' => $this->dailyCounts(
                        Subscription::query()->where('plan', '!=', SubscriptionPlan::FREE->value),
                        'created_at',
                        $days,
                    ),
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.15)',
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
