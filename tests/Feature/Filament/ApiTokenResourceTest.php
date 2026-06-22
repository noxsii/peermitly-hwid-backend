<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\ApiTokens\Pages\ListApiTokens;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Livewire;

test('the api tokens list shows created tokens', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $owner = User::factory()->create();
    $token = $owner->createToken('cli')->accessToken;

    $this->actingAs($admin);

    Livewire::test(ListApiTokens::class)
        ->assertCanSeeTableRecords([$token])
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('last_used_at');
});

test('a token can be revoked from the admin center', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $owner = User::factory()->create();
    $token = $owner->createToken('cli')->accessToken;

    $this->actingAs($admin);

    Livewire::test(ListApiTokens::class)
        ->callTableAction('delete', $token);

    expect(PersonalAccessToken::query()->whereKey($token->getKey())->exists())->toBeFalse();
});
