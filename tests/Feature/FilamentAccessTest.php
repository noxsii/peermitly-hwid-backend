<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;

test('guest is redirected to login when accessing filament panel', function (): void {
    $response = $this->get('/admin');

    $response->assertRedirect('/admin/login');
});

test('regular user gets 403 from filament panel', function (): void {
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('admin role still cannot access filament panel', function (): void {
    $user = User::factory()->create(['role' => UserRole::ADMIN]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('super admin can access filament panel', function (): void {
    $user = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk();
});

test('super admin can list users in filament', function (): void {
    $user = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

    $this->actingAs($user)
        ->get('/admin/users')
        ->assertOk();
});

test('regular user cannot list users in filament', function (): void {
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($user)
        ->get('/admin/users')
        ->assertForbidden();
});

test('super admin can list subscriptions in filament', function (): void {
    $user = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

    $this->actingAs($user)
        ->get('/admin/subscriptions')
        ->assertOk();
});

test('regular user cannot list subscriptions in filament', function (): void {
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($user)
        ->get('/admin/subscriptions')
        ->assertForbidden();
});

test('super admin can list changelogs in filament', function (): void {
    $user = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

    $this->actingAs($user)
        ->get('/admin/changelogs')
        ->assertOk();
});

test('regular user cannot list changelogs in filament', function (): void {
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($user)
        ->get('/admin/changelogs')
        ->assertForbidden();
});
