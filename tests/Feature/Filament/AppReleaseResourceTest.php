<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\AppReleases\Pages\CreateAppRelease;
use App\Filament\Resources\AppReleases\Pages\ListAppReleases;
use App\Models\AppRelease;
use App\Models\User;
use Livewire\Livewire;

test('the releases list shows created releases', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $release = AppRelease::factory()->create(['version' => '1.0.0']);

    $this->actingAs($admin);

    Livewire::test(ListAppReleases::class)
        ->assertCanSeeTableRecords([$release])
        ->assertCanRenderTableColumn('version')
        ->assertCanRenderTableColumn('is_published');
});

test('the create page renders with the platforms repeater', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $this->actingAs($admin);

    Livewire::test(CreateAppRelease::class)
        ->assertOk()
        ->assertFormFieldExists('version')
        ->assertFormFieldExists('platforms');
});

test('version is required and unique', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    AppRelease::factory()->create(['version' => '0.2.0']);
    $this->actingAs($admin);

    Livewire::test(CreateAppRelease::class)
        ->fillForm(['version' => '0.2.0'])
        ->call('create')
        ->assertHasFormErrors(['version' => 'unique']);
});
