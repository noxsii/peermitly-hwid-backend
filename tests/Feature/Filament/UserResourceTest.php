<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Livewire\Livewire;

test('the users list renders the security code column', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

    $this->actingAs($admin);

    Livewire::test(ListUsers::class)
        ->assertCanRenderTableColumn('security_code');
});

test('the edit form shows the current security code', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $user = User::factory()->create(['security_code' => 'AB23']);

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->assertFormSet(['security_code' => 'AB23']);
});

test('an admin can edit the security code', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $user = User::factory()->create(['security_code' => 'AB23']);

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm(['security_code' => 'XY78'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh()->security_code)->toBe('XY78');
});
