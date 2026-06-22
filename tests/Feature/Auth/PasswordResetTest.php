<?php

declare(strict_types=1);

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Inertia\Testing\AssertableInertia;

test('forgot password form renders', function (): void {
    $this->get('/forgot-password')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('auth/ForgotPassword'));
});

test('submitting valid email queues the reset notification', function (): void {
    Notification::fake();

    $user = User::factory()->create(['email' => 'ada@example.com']);

    $this->from('/forgot-password')
        ->post('/forgot-password', ['email' => 'ada@example.com'])
        ->assertRedirect('/forgot-password')
        ->assertSessionHas('status');

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

test('unknown email returns validation error', function (): void {
    Notification::fake();

    $this->from('/forgot-password')
        ->post('/forgot-password', ['email' => 'ghost@example.com'])
        ->assertRedirect('/forgot-password')
        ->assertSessionHasErrors('email');

    Notification::assertNothingSent();
});

test('invalid email format is rejected', function (): void {
    $this->from('/forgot-password')
        ->post('/forgot-password', ['email' => 'not-an-email'])
        ->assertRedirect('/forgot-password')
        ->assertSessionHasErrors('email');
});

test('reset password form renders for a given token', function (): void {
    $this->get('/reset-password/test-token?email=ada@example.com')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->component('auth/ResetPassword')
                ->where('token', 'test-token')
                ->where('email', 'ada@example.com'),
        );
});

test('resetting with a valid token updates the password and redirects to login', function (): void {
    $user = User::factory()->create([
        'email' => 'ada@example.com',
        'password' => 'old-password',
    ]);

    $token = Password::createToken($user);

    $this->from('/reset-password/'.$token)
        ->post('/reset-password', [
            'token' => $token,
            'email' => 'ada@example.com',
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])
        ->assertRedirect('/login')
        ->assertSessionHas('status');

    expect(Hash::check('new-secret-password', $user->fresh()->password))->toBeTrue();
});

test('invalid token is rejected', function (): void {
    User::factory()->create(['email' => 'ada@example.com']);

    $this->from('/reset-password/bogus-token')
        ->post('/reset-password', [
            'token' => 'bogus-token',
            'email' => 'ada@example.com',
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])
        ->assertRedirect('/reset-password/bogus-token')
        ->assertSessionHasErrors('email');
});

test('password confirmation must match', function (): void {
    $user = User::factory()->create(['email' => 'ada@example.com']);
    $token = Password::createToken($user);

    $this->from('/reset-password/'.$token)
        ->post('/reset-password', [
            'token' => $token,
            'email' => 'ada@example.com',
            'password' => 'new-secret-password',
            'password_confirmation' => 'different',
        ])
        ->assertRedirect('/reset-password/'.$token)
        ->assertSessionHasErrors('password');
});
