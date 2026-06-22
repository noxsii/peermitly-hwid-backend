<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;

test('user role enum has expected cases', function (): void {
    expect(UserRole::cases())->toHaveCount(3);
    expect(UserRole::USER->value)->toBe('user');
    expect(UserRole::ADMIN->value)->toBe('admin');
    expect(UserRole::SUPER_ADMIN->value)->toBe('super_admin');
});

test('user model casts role to UserRole enum', function (): void {
    $user = User::factory()->create();

    expect($user->role)->toBeInstanceOf(UserRole::class)->toBe(UserRole::USER);
});

test('user factory admin state assigns ADMIN role', function (): void {
    $user = User::factory()->admin()->create();

    expect($user->role)->toBe(UserRole::ADMIN);
});

test('user factory superAdmin state assigns SUPER_ADMIN role', function (): void {
    $user = User::factory()->superAdmin()->create();

    expect($user->role)->toBe(UserRole::SUPER_ADMIN);
});
