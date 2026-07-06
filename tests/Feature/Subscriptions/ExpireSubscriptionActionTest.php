<?php

declare(strict_types=1);

use App\Actions\Subscriptions\ExpireSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

test('expires an active past-due subscription', function (): void {
    $subscription = Subscription::factory()->pastDue()->create();

    $expired = resolve(ExpireSubscriptionAction::class)->handle($subscription);

    expect($expired)->toBeTrue()
        ->and($subscription->refresh()->status)->toBe(SubscriptionStatus::EXPIRED);
});

test('falls the user back to the free plan after expiry', function (): void {
    $subscription = Subscription::factory()->pastDue()->create();

    resolve(ExpireSubscriptionAction::class)->handle($subscription);

    $active = $subscription->user->activeSubscription;

    expect($active)->not->toBeNull()
        ->and($active->plan)->toBe(SubscriptionPlan::FREE)
        ->and($active->status)->toBe(SubscriptionStatus::ACTIVE);
});

test('does not grant a free plan when another subscription is still active', function (): void {
    $subscription = Subscription::factory()->pastDue()->create();
    $stillRunning = Subscription::factory()
        ->plan(SubscriptionPlan::MONTH)
        ->for($subscription->user)
        ->create();

    resolve(ExpireSubscriptionAction::class)->handle($subscription);

    expect($subscription->user->subscriptions()->count())->toBe(2)
        ->and($subscription->user->activeSubscription->is($stillRunning))->toBeTrue();
});

test('leaves an active subscription that is still running', function (): void {
    $subscription = Subscription::factory()->create();

    $expired = resolve(ExpireSubscriptionAction::class)->handle($subscription);

    expect($expired)->toBeFalse()
        ->and($subscription->refresh()->status)->toBe(SubscriptionStatus::ACTIVE);
});

test('ignores subscriptions that are not active', function (): void {
    $canceled = Subscription::factory()->canceled()->create();

    $expired = resolve(ExpireSubscriptionAction::class)->handle($canceled);

    expect($expired)->toBeFalse()
        ->and($canceled->refresh()->status)->toBe(SubscriptionStatus::CANCELED);
});
