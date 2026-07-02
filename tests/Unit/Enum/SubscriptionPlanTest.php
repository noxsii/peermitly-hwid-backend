<?php

declare(strict_types=1);

use App\Enums\SubscriptionPlan;

test('plan durations in days', function (): void {
    expect(SubscriptionPlan::TRIAL->days())->toBe(14)
        ->and(SubscriptionPlan::DAY->days())->toBe(1)
        ->and(SubscriptionPlan::WEEK->days())->toBe(7)
        ->and(SubscriptionPlan::MONTH->days())->toBe(30);
});

test('trial and lifetime are flagged correctly', function (): void {
    expect(SubscriptionPlan::TRIAL->isTrial())->toBeTrue()
        ->and(SubscriptionPlan::TRIAL->isLifetime())->toBeFalse()
        ->and(SubscriptionPlan::LIFETIME->isLifetime())->toBeTrue()
        ->and(SubscriptionPlan::LIFETIME->isTrial())->toBeFalse()
        ->and(SubscriptionPlan::MONTH->isTrial())->toBeFalse()
        ->and(SubscriptionPlan::MONTH->isLifetime())->toBeFalse();
});

test('lifetime stays active far into the future', function (): void {
    $start = now();

    expect(SubscriptionPlan::LIFETIME->expiresFrom($start)->isFuture())->toBeTrue()
        ->and(SubscriptionPlan::LIFETIME->expiresFrom($start)->gt($start->copy()->addYears(99)))
        ->toBeTrue();
});

test('expiresFrom adds the plan duration to the start', function (): void {
    $start = now();

    expect(
        SubscriptionPlan::WEEK->expiresFrom($start)->equalTo($start->copy()->addDays(7)),
    )->toBeTrue();
});
