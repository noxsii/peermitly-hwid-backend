<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\User;

test('grants an active subscription with plan-based expiry', function (): void {
    $user = User::factory()->create();

    $subscription = resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::MONTH);

    expect($subscription->status)->toBe(SubscriptionStatus::ACTIVE)
        ->and($subscription->plan)->toBe(SubscriptionPlan::MONTH)
        ->and($subscription->ends_at->toDateTimeString())->toBe(now()->addDays(30)->toDateTimeString())
        ->and($user->activeSubscription()->exists())->toBeTrue();
});

test('honours an explicit ends_at override', function (): void {
    $user = User::factory()->create();
    $endsAt = now()->addDays(99);

    $subscription = resolve(GrantSubscriptionAction::class)->handle(
        $user,
        SubscriptionPlan::DAY,
        null,
        $endsAt,
    );

    expect($subscription->ends_at->toDateTimeString())->toBe($endsAt->toDateTimeString());
});
