<?php

declare(strict_types=1);

use App\Models\LicenseKey;
use App\Models\LicenseKeyActivation;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use App\Support\TokenAbility;
use Laravel\Sanctum\Sanctum;

function setupTeamWithKey(array $keyOverrides = []): array
{
    $user = User::factory()->create();
    $team = Team::factory()->ownedBy($user)->create();
    $user->forceFill(['current_team_id' => $team->id])->save();

    $type = LicenseKeyType::factory()->forTeam($team)->create();
    $product = Product::factory()->forTeam($team)->create(['slug' => 'office-efficient']);

    $key = LicenseKey::factory()
        ->forTeam($team)
        ->forType($type)
        ->forProduct($product)
        ->state($keyOverrides)
        ->create();

    return ['user' => $user, 'team' => $team, 'type' => $type, 'product' => $product, 'key' => $key];
}

test('check endpoint requires auth', function (): void {
    $this->postJson('/api/license-keys/check', [
        'key' => 'LIC-ABCD-EFGH',
        'product' => 'office-efficient',
    ])->assertUnauthorized();
});

test('check endpoint requires license-keys:check ability', function (): void {
    ['user' => $user] = setupTeamWithKey();

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_READ]);

    $this->postJson('/api/license-keys/check', [
        'key' => 'LIC-ABCD-EFGH',
        'product' => 'office-efficient',
    ])->assertForbidden();
});

test('check on pending key activates and returns first_activation true', function (): void {
    ['user' => $user, 'key' => $key, 'product' => $product] = setupTeamWithKey();

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $response = $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => $product->slug,
    ]);

    $response->assertOk()
        ->assertJsonPath('valid', true)
        ->assertJsonPath('status', 'valid')
        ->assertJsonPath('first_activation', true);

    expect($key->fresh()->status->value)->toBe('active');
    expect($key->fresh()->activated_at)->not->toBeNull();
    expect($key->fresh()->expires_at)->not->toBeNull();
});

test('second check on active key returns first_activation false with same expires_at', function (): void {
    ['user' => $user, 'key' => $key, 'product' => $product] = setupTeamWithKey();

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => $product->slug,
    ]);

    $originalExpiresAt = $key->fresh()->expires_at;

    $second = $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => $product->slug,
    ]);

    $second->assertOk()
        ->assertJsonPath('valid', true)
        ->assertJsonPath('first_activation', false);

    expect($key->fresh()->expires_at->equalTo($originalExpiresAt))->toBeTrue();
});

test('check returns product_mismatch when product slug does not match', function (): void {
    ['user' => $user, 'key' => $key, 'team' => $team] = setupTeamWithKey();
    Product::factory()->forTeam($team)->create(['slug' => 'other-product']);

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => 'other-product',
    ])->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('status', 'product_mismatch');
});

test('check returns revoked when key is revoked', function (): void {
    ['user' => $user, 'key' => $key, 'product' => $product] = setupTeamWithKey();
    $key->forceFill(['status' => 'revoked', 'revoked_at' => now()])->save();

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => $product->slug,
    ])->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('status', 'revoked');
});

test('check returns expired when expires_at is in the past', function (): void {
    ['user' => $user, 'key' => $key, 'product' => $product] = setupTeamWithKey([
        'status' => 'active',
        'activated_at' => now()->subYear(),
        'expires_at' => now()->subDay(),
    ]);

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => $product->slug,
    ])->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('status', 'expired');
});

test('check returns hwid_required when key requires hwid and none sent', function (): void {
    ['user' => $user, 'key' => $key, 'product' => $product] = setupTeamWithKey([
        'requires_hwid_check' => true,
    ]);

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => $product->slug,
    ])->assertStatus(422)
        ->assertJsonPath('valid', false)
        ->assertJsonPath('status', 'hwid_required');

    expect($key->fresh()->status->value)->toBe('pending');
});

test('check with hwid activates key and registers activation', function (): void {
    ['user' => $user, 'key' => $key, 'product' => $product] = setupTeamWithKey([
        'requires_hwid_check' => true,
    ]);

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => $product->slug,
        'hwid' => 'device-abc-123',
    ])->assertOk()
        ->assertJsonPath('valid', true)
        ->assertJsonPath('first_activation', true);

    expect(LicenseKeyActivation::query()->where('license_key_id', $key->id)->where('machine_id', 'device-abc-123')->exists())
        ->toBeTrue();
});

test('check enforces activation limit when reached', function (): void {
    ['user' => $user, 'key' => $key, 'product' => $product] = setupTeamWithKey([
        'requires_hwid_check' => true,
        'max_activations' => 1,
    ]);

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => $product->slug,
        'hwid' => 'device-1',
    ])->assertOk();

    $second = $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => $product->slug,
        'hwid' => 'device-2',
    ]);

    $second->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('status', 'activation_limit_reached');
});

test('unknown key returns invalid response', function (): void {
    ['user' => $user, 'product' => $product] = setupTeamWithKey();

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => 'LIC-XXXX-YYYY-ZZZZ',
        'product' => $product->slug,
    ])->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('status', 'invalid');
});

test('unknown product returns invalid response', function (): void {
    ['user' => $user, 'key' => $key] = setupTeamWithKey();

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => $key->key,
        'product' => 'nonexistent-product',
    ])->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('status', 'invalid');
});
