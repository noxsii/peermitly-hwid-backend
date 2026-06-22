<?php

declare(strict_types=1);

use App\Actions\Subscriptions\ExpireSubscriptionAction;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

test('expires an active past-due subscription', function (): void {
    $subscription = Subscription::factory()->pastDue()->create();

    $expired = resolve(ExpireSubscriptionAction::class)->handle($subscription);

    expect($expired)->toBeTrue()
        ->and($subscription->refresh()->status)->toBe(SubscriptionStatus::EXPIRED);
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
