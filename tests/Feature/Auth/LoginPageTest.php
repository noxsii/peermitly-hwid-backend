<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;

test('login route responds with 200', function (): void {
    $this->get('/login')->assertOk();
});

test('authenticated user visiting /login is redirected to dashboard', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/login')
        ->assertRedirect(route('dashboard'));
});

test('authenticated user posting to /login is redirected to dashboard', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/login', ['email' => 'a@b.de', 'password' => 'x'])
        ->assertRedirect(route('dashboard'));
});

test('login route renders auth/Login inertia component', function (): void {
    $this->get('/login')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('auth/Login'));
});

test('login route is named login', function (): void {
    expect(route('login', absolute: false))->toBe('/login');
});

test('login post requires email and password', function (): void {
    $this->from('/login')
        ->post('/login', [])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email', 'password']);
});

test('login post rejects invalid email format', function (): void {
    $this->from('/login')
        ->post('/login', ['email' => 'not-an-email', 'password' => 'secret'])
        ->assertSessionHasErrors('email');
});

test('login post with correct credentials authenticates and redirects to dashboard', function (): void {
    $user = User::factory()->create([
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ]);

    $this->post('/login', [
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ])
        ->assertRedirect(route('dashboard'));

    expect(Auth::id())->toBe($user->id);
});

test('login post with wrong password fails with auth error', function (): void {
    User::factory()->create([
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ]);

    $this->from('/login')
        ->post('/login', [
            'email' => 'ada@example.com',
            'password' => 'wrong-password',
        ])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    expect(Auth::check())->toBeFalse();
});

test('login post with unknown email fails with auth error', function (): void {
    $this->from('/login')
        ->post('/login', [
            'email' => 'ghost@example.com',
            'password' => 'whatever',
        ])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    expect(Auth::check())->toBeFalse();
});

test('successful login regenerates session id', function (): void {
    User::factory()->create([
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ]);

    session()->put('foo', 'bar');
    $oldId = session()->getId();

    $this->post('/login', [
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ])->assertRedirect(route('dashboard'));

    expect(session()->getId())->not->toBe($oldId);
});

test('login with remember flag enables remember-me cookie behaviour', function (): void {
    $user = User::factory()->create([
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ]);

    $response = $this->post('/login', [
        'email' => 'ada@example.com',
        'password' => 'correct-password',
        'remember' => true,
    ]);

    $response->assertRedirect(route('dashboard'));
    expect(Auth::user()->getRememberToken())->not->toBeNull();
});

test('intended url is honoured over dashboard default', function (): void {
    User::factory()->create([
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ]);

    $this->get('/dashboard'); // populates intended URL via auth middleware redirect

    $this->post('/login', [
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ])->assertRedirect('/dashboard');
});
