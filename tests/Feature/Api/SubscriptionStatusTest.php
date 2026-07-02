<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('returns valid true with subscription data for an active subscriber', function (): void {
    $user = User::factory()->create(['is_active' => true]);
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);
    Sanctum::actingAs($user, ['app:use']);

    $this->getJson('/api/subscription')
        ->assertOk()
        ->assertJsonPath('valid', true)
        ->assertJsonPath('is_active', true)
        ->assertJsonPath('subscription.plan', 'week')
        ->assertJsonPath('subscription.status', 'active')
        ->assertJsonPath('subscription.days_remaining', 7)
        ->assertJsonStructure([
            'valid',
            'is_active',
            'subscription' => ['plan', 'plan_label', 'status', 'starts_at', 'ends_at', 'days_remaining'],
        ]);
});

test('a trial subscription reports 14 days and the trial flag', function (): void {
    $user = User::factory()->create(['is_active' => true]);
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::TRIAL);
    Sanctum::actingAs($user, ['app:use']);

    $this->getJson('/api/subscription')
        ->assertOk()
        ->assertJsonPath('valid', true)
        ->assertJsonPath('subscription.plan', 'trial')
        ->assertJsonPath('subscription.is_trial', true)
        ->assertJsonPath('subscription.is_lifetime', false)
        ->assertJsonPath('subscription.days_remaining', 14);
});

test('a lifetime subscription is valid with a null days_remaining', function (): void {
    $user = User::factory()->create(['is_active' => true]);
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::LIFETIME);
    Sanctum::actingAs($user, ['app:use']);

    $this->getJson('/api/subscription')
        ->assertOk()
        ->assertJsonPath('valid', true)
        ->assertJsonPath('subscription.plan', 'lifetime')
        ->assertJsonPath('subscription.is_lifetime', true)
        ->assertJsonPath('subscription.days_remaining', null);
});

test('returns valid false and null subscription when none is active', function (): void {
    $user = User::factory()->create(['is_active' => true]);
    Subscription::factory()->for($user)->expired()->create();
    Sanctum::actingAs($user, ['app:use']);

    $this->getJson('/api/subscription')
        ->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('subscription', null);
});

test('returns valid false when the user is inactive even with an active subscription', function (): void {
    $user = User::factory()->create(['is_active' => false]);
    Subscription::factory()->for($user)->create([
        'status' => SubscriptionStatus::ACTIVE,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDays(5),
    ]);
    Sanctum::actingAs($user, ['app:use']);

    $this->getJson('/api/subscription')
        ->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('is_active', false);
});

test('the endpoint requires authentication', function (): void {
    $this->getJson('/api/subscription')->assertUnauthorized();
});
