<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('authenticated user can log out and is redirected to login', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/login');

    expect(Auth::check())->toBeFalse();
});

test('guest cannot post to logout', function (): void {
    $this->post('/logout')->assertRedirect('/login');
});

test('logout route is named logout', function (): void {
    expect(route('logout', absolute: false))->toBe('/logout');
});

test('logout regenerates session token', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);
    session()->put('foo', 'bar');
    $oldToken = session()->token();

    $this->post('/logout');

    expect(session()->token())->not->toBe($oldToken);
});
