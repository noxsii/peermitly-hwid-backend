<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('unauthenticated visitor is redirected to login', function (): void {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated verified user can access dashboard', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('Dashboard'));
});

test('dashboard route is named dashboard', function (): void {
    expect(route('dashboard', absolute: false))->toBe('/dashboard');
});

test('the active subscription is shared on the auth payload', function (): void {
    $user = User::factory()->create();
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('Dashboard')
            ->where('auth.subscription.plan', 'Weekly')
            ->where('auth.subscription.status', 'active')
            ->where('auth.subscription.days_remaining', 7)
            ->has('auth.subscription.ends_at'));
});

test('no subscription is shared when the user has none', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->where('auth.subscription', null));
});

test('an expired subscription is not shared as active access', function (): void {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->expired()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->where('auth.subscription', null));
});
