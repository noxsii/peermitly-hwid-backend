<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('guest requests share null auth user', function (): void {
    $this->get('/login')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->has('auth')
            ->where('auth.user', null));
});

test('authenticated requests share the user with all visible attributes', function (): void {
    $user = User::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->has('auth.user', fn (AssertableInertia $authUser): AssertableInertia => $authUser
                ->where('id', $user->id)
                ->where('name', 'Ada Lovelace')
                ->where('email', 'ada@example.com')
                ->where('role', 'user')
                ->etc()));
});

test('shared auth user never leaks password or remember token', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->has('auth.user', fn (AssertableInertia $authUser): AssertableInertia => $authUser
                ->missing('password')
                ->missing('remember_token')
                ->etc()));
});
