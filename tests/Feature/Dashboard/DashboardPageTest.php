<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

/**
 * Issue the partial reload Inertia performs to resolve the deferred
 * `subscription` prop.
 */
function loadDashboard(User $user): Illuminate\Testing\TestResponse
{
    $version = (string) app(App\Http\Middleware\HandleInertiaRequests::class)->version(request());

    return test()->actingAs($user)->withHeaders([
        'X-Inertia' => 'true',
        'X-Inertia-Version' => $version,
        'X-Inertia-Partial-Component' => 'Dashboard',
        'X-Inertia-Partial-Data' => 'subscription',
    ])->get('/dashboard');
}

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

test('the subscription prop is deferred on the initial load', function (): void {
    $user = User::factory()->create();
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('Dashboard')
            ->missing('subscription'));
});

test('dashboard resolves the active subscription with remaining days', function (): void {
    $user = User::factory()->create();
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);

    loadDashboard($user)
        ->assertOk()
        ->assertJsonPath('component', 'Dashboard')
        ->assertJsonPath('props.subscription.plan', 'Weekly')
        ->assertJsonPath('props.subscription.status', 'active')
        ->assertJsonPath('props.subscription.days_remaining', 7)
        ->assertJsonPath('props.subscription.ends_at', fn (?string $value): bool => is_string($value));
});

test('dashboard resolves no subscription when the user has none', function (): void {
    $user = User::factory()->create();

    loadDashboard($user)
        ->assertOk()
        ->assertJsonPath('component', 'Dashboard')
        ->assertJsonPath('props.subscription', null);
});

test('an expired subscription is not resolved as active access', function (): void {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->expired()->create();

    loadDashboard($user)
        ->assertOk()
        ->assertJsonPath('props.subscription', null);
});
