<?php

declare(strict_types=1);

use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use App\Support\TokenAbility;
use Laravel\Sanctum\Sanctum;

/**
 * Reproduces the manual scenario:
 *   key = LIC-A76U-VN97-AKY4
 *   product = test-test (exists)
 *   call with product = test-test2 (does NOT exist)
 *
 * Documents current behavior.
 */
test('check with non-existent product slug returns invalid (not product_mismatch)', function (): void {
    $user = User::factory()->create();
    $team = Team::factory()->ownedBy($user)->create();
    $user->forceFill(['current_team_id' => $team->id])->save();

    $type = LicenseKeyType::factory()->forTeam($team)->create();
    Product::factory()->forTeam($team)->create(['slug' => 'test-test', 'name' => 'Test']);
    $product = Product::query()->where('team_id', $team->id)->where('slug', 'test-test')->firstOrFail();

    LicenseKey::factory()
        ->forTeam($team)
        ->forType($type)
        ->forProduct($product)
        ->create([
            'key' => 'LIC-A76U-VN97-AKY4',
            'normalized_key' => 'LICA76UVN97AKY4',
            'requires_hwid_check' => true,
        ]);

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $response = $this->postJson('/api/license-keys/check', [
        'key' => 'LIC-A76U-VN97-AKY4',
        'product' => 'test-test2',
        'hwid' => 'string',
    ]);

    $response->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('status', 'invalid');
});

test('check with existing but wrong product returns product_mismatch', function (): void {
    $user = User::factory()->create();
    $team = Team::factory()->ownedBy($user)->create();
    $user->forceFill(['current_team_id' => $team->id])->save();

    $type = LicenseKeyType::factory()->forTeam($team)->create();
    Product::factory()->forTeam($team)->create(['slug' => 'test-test', 'name' => 'Test']);
    Product::factory()->forTeam($team)->create(['slug' => 'other-product', 'name' => 'Other']);
    $productA = Product::query()->where('team_id', $team->id)->where('slug', 'test-test')->firstOrFail();

    LicenseKey::factory()
        ->forTeam($team)
        ->forType($type)
        ->forProduct($productA)
        ->create([
            'key' => 'LIC-A76U-VN97-AKY4',
            'normalized_key' => 'LICA76UVN97AKY4',
            'requires_hwid_check' => true,
        ]);

    Sanctum::actingAs($user, [TokenAbility::LICENSE_KEYS_CHECK]);

    $this->postJson('/api/license-keys/check', [
        'key' => 'LIC-A76U-VN97-AKY4',
        'product' => 'other-product',
        'hwid' => 'string',
    ])
        ->assertOk()
        ->assertJsonPath('valid', false)
        ->assertJsonPath('status', 'product_mismatch');
});
