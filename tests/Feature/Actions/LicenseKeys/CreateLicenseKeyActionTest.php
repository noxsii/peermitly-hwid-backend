<?php

declare(strict_types=1);

use App\Actions\LicenseKeys\CreateLicenseKeyAction;
use App\Enums\LicenseValidityUnit;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;

test('creates pending key with team scope from type', function (): void {
    $user = User::factory()->create();
    $team = Team::factory()->ownedBy($user)->create();
    $type = LicenseKeyType::factory()->forTeam($team)->create();
    $product = Product::factory()->forTeam($team)->create();

    $licenseKey = resolve(CreateLicenseKeyAction::class)->handle(
        $type,
        $product,
        null,
        12,
        LicenseValidityUnit::MONTHS,
        null,
        false,
        null,
        $user,
    );

    expect($licenseKey->status->value)->toBe('pending');
    expect($licenseKey->team_id)->toBe($team->id);
    expect($licenseKey->license_key_type_id)->toBe($type->id);
    expect($licenseKey->product_id)->toBe($product->id);
    expect($licenseKey->activated_at)->toBeNull();
    expect($licenseKey->expires_at)->toBeNull();
});

test('lifetime key has null validity_amount', function (): void {
    $user = User::factory()->create();
    $team = Team::factory()->ownedBy($user)->create();
    $type = LicenseKeyType::factory()->forTeam($team)->create();
    $product = Product::factory()->forTeam($team)->create();

    $licenseKey = resolve(CreateLicenseKeyAction::class)->handle(
        $type,
        $product,
        null,
        99,
        LicenseValidityUnit::LIFETIME,
        null,
        false,
        null,
        $user,
    );

    expect($licenseKey->validity_amount)->toBeNull();
    expect($licenseKey->validity_unit->value)->toBe('lifetime');
});
