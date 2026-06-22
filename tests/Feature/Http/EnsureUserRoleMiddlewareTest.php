<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Http\Middleware\EnsureUserRole;
use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function (): void {
    Route::get('/__test/admin-only', fn (): string => 'ok')
        ->middleware(['web', 'auth', EnsureUserRole::class.':admin']);

    Route::get('/__test/admin-or-super', fn (): string => 'ok')
        ->middleware(['web', 'auth', EnsureUserRole::class.':admin,super_admin']);

    Route::get('/__test/super-only', fn (): string => 'ok')
        ->middleware(['web', 'auth', EnsureUserRole::class.':super_admin']);

    Route::get('/__test/bogus-role', fn (): string => 'ok')
        ->middleware(['web', 'auth', EnsureUserRole::class.':not-a-role']);
});

test('guest is redirected by auth middleware before hitting role check', function (): void {
    $this->get('/__test/admin-only')->assertRedirect('/login');
});

test('user with matching role passes', function (): void {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $this->actingAs($admin)
        ->get('/__test/admin-only')
        ->assertOk()
        ->assertSee('ok');
});

test('user with wrong role gets 403', function (): void {
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($user)
        ->get('/__test/admin-only')
        ->assertForbidden();
});

test('comma-separated roles allow any of them', function (): void {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $super = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($admin)->get('/__test/admin-or-super')->assertOk();
    $this->actingAs($super)->get('/__test/admin-or-super')->assertOk();
    $this->actingAs($user)->get('/__test/admin-or-super')->assertForbidden();
});

test('super admin cannot access admin-only route via wrong role param', function (): void {
    $super = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

    $this->actingAs($super)
        ->get('/__test/admin-only')
        ->assertForbidden();
});

test('only super admin passes super-only', function (): void {
    $super = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $this->actingAs($super)->get('/__test/super-only')->assertOk();
    $this->actingAs($admin)->get('/__test/super-only')->assertForbidden();
});

test('invalid role string is treated as no role and blocks everyone', function (): void {
    $super = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

    $this->actingAs($super)
        ->get('/__test/bogus-role')
        ->assertForbidden();
});

test('role alias works via the registered middleware alias', function (): void {
    Route::get('/__test/alias-admin', fn (): string => 'ok')
        ->middleware(['web', 'auth', 'role:admin']);

    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($admin)->get('/__test/alias-admin')->assertOk();
    $this->actingAs($user)->get('/__test/alias-admin')->assertForbidden();
});
