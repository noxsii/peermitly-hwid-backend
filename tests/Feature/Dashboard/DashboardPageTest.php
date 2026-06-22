<?php

declare(strict_types=1);

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
