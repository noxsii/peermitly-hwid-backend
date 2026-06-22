<?php

declare(strict_types=1);

use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use App\Support\TokenAbility;
use Laravel\Sanctum\Sanctum;

function actingAsTeamUser(array $abilities = []): array
{
    $user = User::factory()->create();
    $team = Team::factory()->ownedBy($user)->create();
    $user->forceFill(['current_team_id' => $team->id])->save();

    Sanctum::actingAs($user, $abilities);

    return ['user' => $user, 'team' => $team];
}

test('list license keys requires read ability', function (): void {
    actingAsTeamUser([TokenAbility::LICENSE_KEYS_MANAGE]);

    $this->getJson('/api/license-keys')->assertForbidden();
});

test('list license keys returns team-scoped data', function (): void {
    ['team' => $team] = actingAsTeamUser([TokenAbility::LICENSE_KEYS_READ]);

    LicenseKey::factory()->forTeam($team)->count(3)->create();
    LicenseKey::factory()->count(2)->create(); // other team

    $this->getJson('/api/license-keys')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('show returns 404 for foreign team key', function (): void {
    actingAsTeamUser([TokenAbility::LICENSE_KEYS_READ]);

    $foreign = LicenseKey::factory()->create();

    $this->getJson('/api/license-keys/'.$foreign->uuid)->assertNotFound();
});

test('store creates pending license key', function (): void {
    ['team' => $team] = actingAsTeamUser([TokenAbility::LICENSE_KEYS_MANAGE]);
    $type = LicenseKeyType::factory()->forTeam($team)->create();
    $product = Product::factory()->forTeam($team)->create();

    $this->postJson('/api/license-keys', [
        'license_key_type_uuid' => $type->uuid,
        'product_uuid' => $product->uuid,
        'validity_amount' => 12,
        'validity_unit' => 'months',
        'requires_hwid_check' => false,
    ])->assertSuccessful()
        ->assertJsonPath('data.status', 'pending');
});

test('revoke marks key as revoked', function (): void {
    ['team' => $team] = actingAsTeamUser([TokenAbility::LICENSE_KEYS_MANAGE]);
    $key = LicenseKey::factory()->forTeam($team)->active()->create();

    $this->postJson('/api/license-keys/'.$key->uuid.'/revoke', [
        'reason' => 'Test revoke',
    ])->assertOk()
        ->assertJsonPath('data.status', 'revoked');

    expect($key->fresh()->revoked_reason)->toBe('Test revoke');
});

test('extend pushes expires_at forward for active key', function (): void {
    ['team' => $team] = actingAsTeamUser([TokenAbility::LICENSE_KEYS_MANAGE]);
    $key = LicenseKey::factory()->forTeam($team)->active()->create();
    $originalExpires = $key->expires_at;

    $this->postJson('/api/license-keys/'.$key->uuid.'/extend', [
        'amount' => 6,
        'unit' => 'months',
    ])->assertOk();

    expect($key->fresh()->expires_at->greaterThan($originalExpires))->toBeTrue();
});

test('restore brings revoked key back to active', function (): void {
    ['team' => $team] = actingAsTeamUser([TokenAbility::LICENSE_KEYS_MANAGE]);
    $key = LicenseKey::factory()->forTeam($team)->revoked()->create();

    $this->postJson('/api/license-keys/'.$key->uuid.'/restore')
        ->assertOk()
        ->assertJsonPath('data.status', 'active');
});

test('license key type CRUD requires its own ability', function (): void {
    actingAsTeamUser([TokenAbility::LICENSE_KEYS_MANAGE]);

    $this->getJson('/api/license-key-types')->assertForbidden();
});

test('list license key types returns team data', function (): void {
    ['team' => $team] = actingAsTeamUser([TokenAbility::LICENSE_KEY_TYPES_MANAGE]);
    LicenseKeyType::factory()->forTeam($team)->count(2)->create();

    $this->getJson('/api/license-key-types')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});
