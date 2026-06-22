<?php

declare(strict_types=1);

use App\Actions\LicenseKeys\ExtendLicenseKeyAction;
use App\Enums\LicenseValidityUnit;
use App\Models\LicenseKey;

test('pending key extension changes only validity amount and unit', function (): void {
    $key = LicenseKey::factory()->create([
        'status' => 'pending',
        'validity_amount' => 12,
        'validity_unit' => 'months',
    ]);

    $extended = new ExtendLicenseKeyAction()->handle($key, 24, LicenseValidityUnit::MONTHS);

    expect($extended->validity_amount)->toBe(24);
    expect($extended->expires_at)->toBeNull();
});

test('active key extension pushes expires_at forward by amount', function (): void {
    $key = LicenseKey::factory()->active()->create();
    $originalExpires = $key->expires_at;

    $extended = new ExtendLicenseKeyAction()->handle($key, 6, LicenseValidityUnit::MONTHS);

    expect($extended->expires_at->format('Y-m-d H:i:s'))->toBe($originalExpires->copy()->addMonths(6)->format('Y-m-d H:i:s'));
});

test('expired key extension starts new period from now', function (): void {
    $key = LicenseKey::factory()->expired()->create();

    $extended = new ExtendLicenseKeyAction()->handle($key, 12, LicenseValidityUnit::MONTHS);

    expect($extended->status->value)->toBe('active');
    expect($extended->expires_at->format('Y-m-d H:i:s'))->toBe(now()->addMonths(12)->format('Y-m-d H:i:s'));
});

test('extension to lifetime nullifies expires_at', function (): void {
    $key = LicenseKey::factory()->active()->create();

    $extended = new ExtendLicenseKeyAction()->handle($key, 1, LicenseValidityUnit::LIFETIME);

    expect($extended->expires_at)->toBeNull();
    expect($extended->validity_unit->value)->toBe('lifetime');
});

test('extension by hours pushes expires_at forward by hours', function (): void {
    $key = LicenseKey::factory()->active()->create();
    $originalExpires = $key->expires_at;

    $extended = new ExtendLicenseKeyAction()->handle($key, 5, LicenseValidityUnit::HOURS);

    expect($extended->expires_at->format('Y-m-d H:i:s'))
        ->toBe($originalExpires->copy()->addHours(5)->format('Y-m-d H:i:s'));
    expect($extended->validity_unit->value)->toBe('hours');
    expect($extended->validity_amount)->toBe(5);
});
