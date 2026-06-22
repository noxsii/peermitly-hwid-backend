<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
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

test('dashboard shows the active subscription with remaining days', function (): void {
    $user = User::factory()->create();
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('Dashboard')
            ->where('subscription.plan', 'Weekly')
            ->where('subscription.status', 'active')
            ->where('subscription.days_remaining', 7)
            ->has('subscription.ends_at'));
});

test('dashboard shows no subscription when the user has none', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('Dashboard')
            ->where('subscription', null));
});

test('an expired subscription is not shown as active access', function (): void {
    $user = User::factory()->create();
    App\Models\Subscription::factory()->for($user)->expired()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->where('subscription', null));
});
