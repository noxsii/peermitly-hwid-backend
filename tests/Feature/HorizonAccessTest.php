<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;

test('guests cannot access the horizon dashboard', function (): void {
    $this->get('/horizon')->assertForbidden();
});

test('regular users cannot access the horizon dashboard', function (): void {
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($user)->get('/horizon')->assertForbidden();
});

test('admins cannot access the horizon dashboard', function (): void {
    $user = User::factory()->create(['role' => UserRole::ADMIN]);

    $this->actingAs($user)->get('/horizon')->assertForbidden();
});

test('inactive super admins cannot access the horizon dashboard', function (): void {
    $user = User::factory()->create([
        'role' => UserRole::SUPER_ADMIN,
        'is_active' => false,
    ]);

    $this->actingAs($user)->get('/horizon')->assertForbidden();
});

test('active super admins can access the horizon dashboard', function (): void {
    $user = User::factory()->create([
        'role' => UserRole::SUPER_ADMIN,
        'is_active' => true,
    ]);

    $this->actingAs($user)->get('/horizon')->assertSuccessful();
});
