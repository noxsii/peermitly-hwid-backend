<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\SubscriptionPlan;
use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

final class SubscriptionPlansChart extends ChartWidget
{
    private const array PLAN_COLORS = [
        'free' => '#a1a1aa',
        'trial' => '#f59e0b',
        'day' => '#0ea5e9',
        'week' => '#3b82f6',
        'month' => '#f97316',
        'lifetime' => '#8b5cf6',
    ];

    protected static ?int $sort = 2;

    protected ?string $heading = 'Aktive Abonnements nach Plan';

    protected ?string $maxHeight = '300px';

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $options = [
        'cutout' => '65%',
        'plugins' => [
            'legend' => ['position' => 'bottom'],
        ],
    ];

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        /** @var array<string, int> $counts */
        $counts = Subscription::whereActive()
            ->selectRaw('plan, count(*) as total')
            ->groupBy('plan')
            ->pluck('total', 'plan')
            ->all();

        $plans = array_values(array_filter(
            SubscriptionPlan::cases(),
            static fn (SubscriptionPlan $plan): bool => ($counts[$plan->value] ?? 0) > 0,
        ));

        return [
            'datasets' => [
                [
                    'label' => 'Aktive Abonnements',
                    'data' => array_map(
                        static fn (SubscriptionPlan $plan): int => $counts[$plan->value] ?? 0,
                        $plans,
                    ),
                    'backgroundColor' => array_map(
                        static fn (SubscriptionPlan $plan): string => self::PLAN_COLORS[$plan->value],
                        $plans,
                    ),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => array_map(
                static fn (SubscriptionPlan $plan): string => $plan->label(),
                $plans,
            ),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
