<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;

test('settings page renders for authed user', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('Settings'));
});

test('guest cannot access settings page', function (): void {
    $this->get('/settings')->assertRedirect('/login');
});

test('successful password update logs the user out and redirects to login', function (): void {
    $user = User::factory()->create([
        'password' => 'current-password',
    ]);

    $this->actingAs($user)
        ->from('/settings')
        ->put('/settings/password', [
            'current_password' => 'current-password',
            'password' => 'new-strong-password',
            'password_confirmation' => 'new-strong-password',
        ])
        ->assertRedirect('/login')
        ->assertSessionHas('status', 'password-updated');

    expect(Hash::check('new-strong-password', $user->fresh()->password))->toBeTrue();
    expect(Auth::check())->toBeFalse();
});

test('password update fails when current password is wrong', function (): void {
    $user = User::factory()->create(['password' => 'current-password']);

    $this->actingAs($user)
        ->from('/settings')
        ->put('/settings/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-strong-password',
            'password_confirmation' => 'new-strong-password',
        ])
        ->assertRedirect('/settings')
        ->assertSessionHasErrors('current_password');

    expect(Hash::check('current-password', $user->fresh()->password))->toBeTrue();
});

test('password update fails when confirmation does not match', function (): void {
    $user = User::factory()->create(['password' => 'current-password']);

    $this->actingAs($user)
        ->from('/settings')
        ->put('/settings/password', [
            'current_password' => 'current-password',
            'password' => 'new-strong-password',
            'password_confirmation' => 'mismatch',
        ])
        ->assertSessionHasErrors('password');
});

test('password update fails when new password too short', function (): void {
    $user = User::factory()->create(['password' => 'current-password']);

    $this->actingAs($user)
        ->from('/settings')
        ->put('/settings/password', [
            'current_password' => 'current-password',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])
        ->assertSessionHasErrors('password');
});

test('route names resolve correctly', function (): void {
    expect(route('settings.edit', absolute: false))->toBe('/settings');
    expect(route('settings.password.update', absolute: false))->toBe('/settings/password');
});
