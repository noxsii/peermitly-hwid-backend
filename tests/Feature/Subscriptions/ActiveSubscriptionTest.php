<?php

declare(strict_types=1);

use App\Enums\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\User;

test('a current active subscription grants access', function (): void {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->plan(SubscriptionPlan::WEEK)->create();

    expect($user->activeSubscription()->exists())->toBeTrue();
});

test('expired subscriptions do not grant access', function (): void {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->expired()->create();

    expect($user->activeSubscription()->exists())->toBeFalse();
});

test('canceled subscriptions do not grant access', function (): void {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->canceled()->create();

    expect($user->activeSubscription()->exists())->toBeFalse();
});

test('the active subscription is the one running longest', function (): void {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->plan(SubscriptionPlan::DAY)->create();
    Subscription::factory()->for($user)->plan(SubscriptionPlan::MONTH)->create();

    expect($user->activeSubscription->plan)->toBe(SubscriptionPlan::MONTH);
});
