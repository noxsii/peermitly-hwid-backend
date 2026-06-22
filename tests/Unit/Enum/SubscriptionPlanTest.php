<?php

declare(strict_types=1);

use App\Enums\SubscriptionPlan;

test('plan durations in days', function (): void {
    expect(SubscriptionPlan::DAY->days())->toBe(1)
        ->and(SubscriptionPlan::WEEK->days())->toBe(7)
        ->and(SubscriptionPlan::MONTH->days())->toBe(30);
});

test('expiresFrom adds the plan duration to the start', function (): void {
    $start = now();

    expect(
        SubscriptionPlan::WEEK->expiresFrom($start)->equalTo($start->copy()->addDays(7)),
    )->toBeTrue();
});
