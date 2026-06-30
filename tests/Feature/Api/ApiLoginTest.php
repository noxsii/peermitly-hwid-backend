<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Date;

beforeEach(function (): void {
    // Reset the throttle counters between tests.
    cache()->flush();
});

function activeApiUser(): User
{
    $user = User::factory()->create([
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'is_active' => true,
    ]);
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);

    return $user;
}

test('active user with an active subscription receives a token, user and subscription', function (): void {
    $user = activeApiUser();

    $response = $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'device_name' => 'Gaming Rig',
        'hwid' => 'hwid-abc',
    ]);

    $response->assertOk()
        ->assertJsonPath('user.email', 'player@example.com')
        ->assertJsonPath('user.is_active', true)
        ->assertJsonPath('subscription.plan', 'week')
        ->assertJsonPath('subscription.days_remaining', 7)
        ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'role'], 'subscription' => ['plan', 'ends_at']]);

    expect($response->json('token'))->toBeString()->not->toBeEmpty();

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'name' => 'Gaming Rig',
    ]);
});

test('login days_remaining counts calendar days and is not inflated by time of day', function (): void {
    $this->travelTo(Date::parse('2026-06-23 08:00:00'));

    $user = User::factory()->create([
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'is_active' => true,
    ]);
    Subscription::factory()->for($user)->create([
        'status' => SubscriptionStatus::ACTIVE,
        'starts_at' => now()->subDay(),
        'ends_at' => Date::parse('2026-07-22 09:00:00'),
    ]);

    $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-abc',
    ])->assertOk()->assertJsonPath('subscription.days_remaining', 29);

    $this->travelBack();
});

test('the issued token authenticates subsequent requests', function (): void {
    activeApiUser();

    $token = $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-abc',
    ])->json('token');

    $this->withToken($token)
        ->withHeader('X-HWID', 'hwid-abc')
        ->getJson('/api/user')
        ->assertOk()
        ->assertJsonPath('email', 'player@example.com');
});

test('wrong credentials are rejected', function (): void {
    activeApiUser();

    $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'wrong-password',
        'hwid' => 'hwid-abc',
    ])->assertStatus(422)->assertJsonValidationErrors('email');
});

test('unknown email is rejected', function (): void {
    $this->postJson('/api/login', [
        'email' => 'ghost@example.com',
        'password' => 'whatever',
        'hwid' => 'hwid-abc',
    ])->assertStatus(422)->assertJsonValidationErrors('email');
});

test('inactive users cannot sign in even with a subscription', function (): void {
    $user = User::factory()->create([
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'is_active' => false,
    ]);
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);

    $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-abc',
    ])->assertForbidden();
});

test('active users without a subscription cannot sign in', function (): void {
    User::factory()->create([
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'is_active' => true,
    ]);

    $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-abc',
    ])->assertForbidden();
});

test('an expired subscription does not grant sign in', function (): void {
    $user = User::factory()->create([
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'is_active' => true,
    ]);
    Subscription::factory()->for($user)->expired()->create();

    $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-abc',
    ])->assertForbidden();
});

test('login validates required fields', function (): void {
    $this->postJson('/api/login', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password', 'hwid']);
});

test('the first login binds the account to the supplied hwid', function (): void {
    $user = activeApiUser();

    $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-first',
    ])->assertOk();

    expect($user->refresh()->hwid)->toBe('hwid-first');
});

test('a login from a different device is rejected once bound', function (): void {
    $user = activeApiUser();
    $user->forceFill(['hwid' => 'hwid-first'])->save();

    $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-second',
    ])->assertForbidden();
});

test('a login from the bound device is allowed and revokes the previous session', function (): void {
    $user = activeApiUser();

    $first = $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-first',
    ])->json('token');

    $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-first',
    ])->assertOk();

    expect($user->tokens()->count())->toBe(1);

    $this->withToken($first)
        ->withHeader('X-HWID', 'hwid-first')
        ->getJson('/api/user')
        ->assertUnauthorized();
});

test('the user endpoint never exposes the security code or hwid', function (): void {
    activeApiUser();

    $token = $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-first',
    ])->json('token');

    $this->withToken($token)
        ->withHeader('X-HWID', 'hwid-first')
        ->getJson('/api/user')
        ->assertOk()
        ->assertJsonMissingPath('security_code')
        ->assertJsonMissingPath('hwid');
});

test('an authed request with a mismatched hwid header is rejected', function (): void {
    activeApiUser();

    $token = $this->postJson('/api/login', [
        'email' => 'player@example.com',
        'password' => 'correct-password',
        'hwid' => 'hwid-first',
    ])->json('token');

    $this->withToken($token)
        ->withHeader('X-HWID', 'hwid-other')
        ->getJson('/api/user')
        ->assertForbidden();
});

test('login is rate limited', function (): void {
    foreach (range(1, 6) as $ignored) {
        $this->postJson('/api/login', [
            'email' => 'ghost@example.com',
            'password' => 'whatever',
        ]);
    }

    $this->postJson('/api/login', [
        'email' => 'ghost@example.com',
        'password' => 'whatever',
    ])->assertStatus(429);
});
