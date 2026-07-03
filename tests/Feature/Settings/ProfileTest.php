<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

test('the profile page renders for an authenticated user', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/profile')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Profile'));
});

test('a user can update their name', function (): void {
    $user = User::factory()->create(['name' => 'Old Name']);

    actingAs($user)
        ->from('/profile')
        ->put('/profile', ['name' => 'New Name'])
        ->assertRedirect('/profile')
        ->assertSessionHas('status', 'profile-updated');

    assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
});

test('the name is required', function (): void {
    $user = User::factory()->create(['name' => 'Old Name']);

    actingAs($user)
        ->from('/profile')
        ->put('/profile', ['name' => ''])
        ->assertSessionHasErrors('name');

    assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Old Name']);
});

test('guests cannot access the profile page', function (): void {
    $this->get('/profile')->assertRedirect('/login');
});
