<?php

declare(strict_types=1);

use App\Actions\Dashboard\GetDashboardStatsAction;
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

test('GetDashboardStatsAction counts users', function (): void {
    User::factory()->count(3)->create();
    User::factory()->create(['is_active' => false]);
    User::factory()->unverified()->create();

    $stats = resolve(GetDashboardStatsAction::class)->handle();

    expect($stats->totalUsers)->toBe(5)
        ->and($stats->activeUsers)->toBe(4)
        ->and($stats->verifiedUsers)->toBe(4);
});
