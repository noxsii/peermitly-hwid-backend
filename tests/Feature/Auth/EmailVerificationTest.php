<?php

declare(strict_types=1);

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

function verificationUrl(User $user, ?Carbon $expires = null): string
{
    return URL::temporarySignedRoute(
        'verification.verify',
        $expires ?? now()->addHours(3),
        ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())],
    );
}

test('a valid link confirms the email and activates the account', function (): void {
    Event::fake();

    $user = User::factory()->unverified()->create(['is_active' => false]);

    get(verificationUrl($user))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('auth/EmailConfirmed')
            ->where('state', 'confirmed'));

    $user->refresh();

    expect($user->hasVerifiedEmail())->toBeTrue()
        ->and($user->is_active)->toBeTrue();

    Event::assertDispatched(Verified::class);
});

test('an expired link is rejected and leaves the account untouched', function (): void {
    $user = User::factory()->unverified()->create(['is_active' => false]);

    get(verificationUrl($user, now()->subMinute()))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('auth/EmailConfirmed')
            ->where('state', 'invalid'));

    $user->refresh();

    expect($user->hasVerifiedEmail())->toBeFalse()
        ->and($user->is_active)->toBeFalse();
});

test('a tampered hash is rejected', function (): void {
    $user = User::factory()->unverified()->create(['is_active' => false]);

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addHours(3),
        ['id' => $user->getKey(), 'hash' => sha1('wrong@example.com')],
    );

    get($url)->assertInertia(fn ($page) => $page->where('state', 'invalid'));

    expect($user->refresh()->hasVerifiedEmail())->toBeFalse();
});

test('an already verified email reports the already state', function (): void {
    $user = User::factory()->create();

    get(verificationUrl($user))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('state', 'already'));
});

test('a signed-in unverified user can request a fresh link', function (): void {
    Notification::fake();

    $user = User::factory()->unverified()->create(['is_active' => false]);

    actingAs($user)
        ->post('/email/verification-notification')
        ->assertRedirect();

    Notification::assertSentTo($user, VerifyEmailNotification::class);
});
