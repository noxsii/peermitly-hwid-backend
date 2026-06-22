<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('license keys index requires authentication', function (): void {
    $this->get('/license-keys')->assertRedirect('/login');
});

test('license keys index renders for authenticated user', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/license-keys')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('license-keys/Index'));
});

test('license keys route is named license-keys.index', function (): void {
    expect(route('license-keys.index', absolute: false))->toBe('/license-keys');
});
