<?php

declare(strict_types=1);

use App\Enums\SubscriptionPlan;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('the register page renders', function (): void {
    get('/register')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('auth/Register'));
});

test('a guest can register and gets an inactive, unverified account with a free subscription', function (): void {
    Notification::fake();

    post('/register', [
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'password' => 'super-secret-123',
        'password_confirmation' => 'super-secret-123',
    ])->assertRedirect(route('login'));

    assertDatabaseHas('users', [
        'email' => 'ada@example.com',
        'is_active' => false,
        'email_verified_at' => null,
    ]);

    $user = User::query()->where('email', 'ada@example.com')->sole();

    expect($user->subscriptions()->first()?->plan)->toBe(SubscriptionPlan::FREE);

    Notification::assertSentTo($user, VerifyEmailNotification::class);
});

test('the email is lowercased on registration', function (): void {
    Notification::fake();

    post('/register', [
        'name' => 'Ada',
        'email' => 'ADA@Example.COM',
        'password' => 'super-secret-123',
        'password_confirmation' => 'super-secret-123',
    ])->assertRedirect(route('login'));

    assertDatabaseHas('users', ['email' => 'ada@example.com']);
});

test('registration fails when the passwords do not match', function (): void {
    Notification::fake();

    post('/register', [
        'name' => 'Ada',
        'email' => 'ada@example.com',
        'password' => 'super-secret-123',
        'password_confirmation' => 'different-456',
    ])->assertSessionHasErrors('password');

    Notification::assertNothingSent();
});

test('registration fails when the email is already taken', function (): void {
    User::factory()->create(['email' => 'ada@example.com']);

    post('/register', [
        'name' => 'Ada',
        'email' => 'ada@example.com',
        'password' => 'super-secret-123',
        'password_confirmation' => 'super-secret-123',
    ])->assertSessionHasErrors('email');
});
