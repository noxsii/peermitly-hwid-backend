<?php

declare(strict_types=1);

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function (): void {
    Route::middleware(['web', 'auth', 'subscribed'])
        ->get('/_test/secure', fn (): string => 'ok');
});

test('guests are redirected to login', function (): void {
    $this->get('/_test/secure')->assertRedirect('/login');
});

test('users without an active subscription are forbidden', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/_test/secure')->assertForbidden();
});

test('users with an active subscription pass', function (): void {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->create();

    $this->actingAs($user)->get('/_test/secure')->assertOk();
});

test('users with only an expired subscription are forbidden', function (): void {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->expired()->create();

    $this->actingAs($user)->get('/_test/secure')->assertForbidden();
});
