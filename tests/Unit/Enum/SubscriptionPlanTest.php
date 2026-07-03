<?php

declare(strict_types=1);

use App\Enums\SubscriptionPlan;

test('plan durations in days', function (): void {
    expect(SubscriptionPlan::TRIAL->days())->toBe(14)
        ->and(SubscriptionPlan::DAY->days())->toBe(1)
        ->and(SubscriptionPlan::WEEK->days())->toBe(7)
        ->and(SubscriptionPlan::MONTH->days())->toBe(30)
        ->and(SubscriptionPlan::FREE->days())->toBe(36_525);
});

test('trial and lifetime are flagged correctly', function (): void {
    expect(SubscriptionPlan::TRIAL->isTrial())->toBeTrue()
        ->and(SubscriptionPlan::TRIAL->isLifetime())->toBeFalse()
        ->and(SubscriptionPlan::LIFETIME->isLifetime())->toBeTrue()
        ->and(SubscriptionPlan::LIFETIME->isTrial())->toBeFalse()
        ->and(SubscriptionPlan::MONTH->isTrial())->toBeFalse()
        ->and(SubscriptionPlan::MONTH->isLifetime())->toBeFalse();
});

test('free is flagged as non-pro and perpetual', function (): void {
    expect(SubscriptionPlan::FREE->isFree())->toBeTrue()
        ->and(SubscriptionPlan::FREE->isPro())->toBeFalse()
        ->and(SubscriptionPlan::FREE->isPerpetual())->toBeTrue()
        ->and(SubscriptionPlan::FREE->isLifetime())->toBeFalse();
});

test('pro plans are flagged as pro and only free/lifetime are perpetual', function (): void {
    expect(SubscriptionPlan::MONTH->isPro())->toBeTrue()
        ->and(SubscriptionPlan::MONTH->isFree())->toBeFalse()
        ->and(SubscriptionPlan::MONTH->isPerpetual())->toBeFalse()
        ->and(SubscriptionPlan::LIFETIME->isPro())->toBeTrue()
        ->and(SubscriptionPlan::LIFETIME->isPerpetual())->toBeTrue();
});

test('free stays active far into the future', function (): void {
    $start = now();

    expect(SubscriptionPlan::FREE->expiresFrom($start)->isFuture())->toBeTrue()
        ->and(SubscriptionPlan::FREE->expiresFrom($start)->gt($start->copy()->addYears(99)))
        ->toBeTrue();
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
