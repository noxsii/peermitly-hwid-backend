<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Date;
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

test('the security code is shared with the dashboard', function (): void {
    $user = User::factory()->create(['security_code' => 'AB23']);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('Dashboard')
            ->where('securityCode', 'AB23'));
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
            ->where('auth.subscription.is_lifetime', false)
            ->has('auth.subscription.ends_at'));
});

test('a lifetime subscription is flagged as lifetime', function (): void {
    $user = User::factory()->create();
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::LIFETIME);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->where('auth.subscription.plan', 'Lifetime')
            ->where('auth.subscription.is_lifetime', true));
});

test('days_remaining counts calendar days and is not inflated by time of day', function (): void {
    $this->travelTo(Date::parse('2026-06-23 08:00:00'));

    $user = User::factory()->create();
    Subscription::factory()->for($user)->create([
        'status' => SubscriptionStatus::ACTIVE,
        'starts_at' => now()->subDay(),
        'ends_at' => Date::parse('2026-07-22 09:00:00'),
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->where('auth.subscription.days_remaining', 29));

    $this->travelBack();
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
