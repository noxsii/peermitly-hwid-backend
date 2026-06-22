<?php

declare(strict_types=1);

use App\Actions\LicenseKeys\BulkExtendLicenseKeysAction;
use App\Data\LicenseKeys\BulkExtendCriteria;
use App\Enums\LicenseKeyStatus;
use App\Enums\LicenseValidityUnit;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\Team;
use Illuminate\Support\Facades\Date;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->otherTeam = Team::factory()->create();
    $this->product = Product::factory()->forTeam($this->team)->create();
    $this->type = LicenseKeyType::factory()->forTeam($this->team)->create();
    $this->action = resolve(BulkExtendLicenseKeysAction::class);
});

test('extends only keys expiring on or after the threshold', function (): void {
    $threshold = now()->addDays(10);

    $shouldExtend = LicenseKey::factory()
        ->forTeam($this->team)
        ->active()
        ->state([
            'license_key_type_id' => $this->type->id,
            'product_id' => $this->product->id,
            'expires_at' => now()->addDays(15),
        ])->create();

    $tooEarly = LicenseKey::factory()
        ->forTeam($this->team)
        ->active()
        ->state([
            'license_key_type_id' => $this->type->id,
            'product_id' => $this->product->id,
            'expires_at' => now()->addDays(5),
        ])->create();

    $originalTooEarly = $tooEarly->expires_at;
    $originalShould = $shouldExtend->expires_at;

    $count = $this->action->handle(new BulkExtendCriteria(
        teamId: $this->team->id,
        fromExpiresAt: $threshold,
        amount: 2,
        unit: LicenseValidityUnit::MONTHS,
    ));

    expect($count)->toBe(1);
    expect($shouldExtend->fresh()->expires_at->greaterThan($originalShould))->toBeTrue();
    expect($tooEarly->fresh()->expires_at->equalTo($originalTooEarly))->toBeTrue();
});

test('ignores lifetime keys', function (): void {
    $lifetime = LicenseKey::factory()
        ->forTeam($this->team)
        ->lifetime()
        ->state([
            'license_key_type_id' => $this->type->id,
            'product_id' => $this->product->id,
        ])->create();

    $count = $this->action->handle(new BulkExtendCriteria(
        teamId: $this->team->id,
        fromExpiresAt: now()->subYear(),
        amount: 5,
        unit: LicenseValidityUnit::DAYS,
    ));

    expect($count)->toBe(0);
    expect($lifetime->fresh()->expires_at)->toBeNull();
    expect($lifetime->fresh()->validity_unit)->toBe(LicenseValidityUnit::LIFETIME);
});

test('ignores keys without expires_at', function (): void {
    LicenseKey::factory()
        ->forTeam($this->team)
        ->state([
            'license_key_type_id' => $this->type->id,
            'product_id' => $this->product->id,
            'status' => LicenseKeyStatus::PENDING->value,
            'expires_at' => null,
        ])->create();

    $count = $this->action->handle(new BulkExtendCriteria(
        teamId: $this->team->id,
        fromExpiresAt: now()->subYear(),
        amount: 1,
        unit: LicenseValidityUnit::DAYS,
    ));

    expect($count)->toBe(0);
});

test('does not touch other teams keys', function (): void {
    $otherProduct = Product::factory()->forTeam($this->otherTeam)->create();
    $otherType = LicenseKeyType::factory()->forTeam($this->otherTeam)->create();

    $otherKey = LicenseKey::factory()
        ->forTeam($this->otherTeam)
        ->active()
        ->state([
            'license_key_type_id' => $otherType->id,
            'product_id' => $otherProduct->id,
            'expires_at' => now()->addDays(20),
        ])->create();
    $originalExpires = $otherKey->expires_at;

    $count = $this->action->handle(new BulkExtendCriteria(
        teamId: $this->team->id,
        fromExpiresAt: now(),
        amount: 1,
        unit: LicenseValidityUnit::MONTHS,
    ));

    expect($count)->toBe(0);
    expect($otherKey->fresh()->expires_at->equalTo($originalExpires))->toBeTrue();
});

test('pushes expires_at forward by the requested duration', function (): void {
    $originalIso = now()->addDays(20)->startOfMinute()->toIso8601String();
    $key = LicenseKey::factory()
        ->forTeam($this->team)
        ->active()
        ->state([
            'license_key_type_id' => $this->type->id,
            'product_id' => $this->product->id,
            'expires_at' => $originalIso,
        ])->create();

    $this->action->handle(new BulkExtendCriteria(
        teamId: $this->team->id,
        fromExpiresAt: now(),
        amount: 3,
        unit: LicenseValidityUnit::DAYS,
    ));

    $expected = Date::parse($originalIso)->addDays(3);
    expect($key->fresh()->expires_at->equalTo($expected))->toBeTrue();
});
