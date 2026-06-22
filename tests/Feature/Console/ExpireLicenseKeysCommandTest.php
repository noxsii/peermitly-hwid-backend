<?php

declare(strict_types=1);

use App\Models\LicenseKey;

test('active keys with past expires_at are marked expired', function (): void {
    $expired = LicenseKey::factory()->create([
        'status' => 'active',
        'activated_at' => now()->subYear(),
        'expires_at' => now()->subDay(),
    ]);
    $stillActive = LicenseKey::factory()->active()->create();
    $pending = LicenseKey::factory()->create([
        'status' => 'pending',
        'activated_at' => null,
        'expires_at' => null,
    ]);

    $this->artisan('license-keys:expire')
        ->expectsOutputToContain('Expired 1 license keys.')
        ->assertSuccessful();

    expect($expired->fresh()->status->value)->toBe('expired');
    expect($stillActive->fresh()->status->value)->toBe('active');
    expect($pending->fresh()->status->value)->toBe('pending');
});

test('lifetime keys with null expires_at are never expired', function (): void {
    $lifetime = LicenseKey::factory()->lifetime()->create();

    $this->artisan('license-keys:expire')->assertSuccessful();

    expect($lifetime->fresh()->status->value)->toBe('active');
});

test('hours-based keys expire when timestamp is past', function (): void {
    $key = LicenseKey::factory()->create([
        'status' => 'active',
        'validity_amount' => 2,
        'validity_unit' => 'hours',
        'activated_at' => now()->subHours(3),
        'expires_at' => now()->subHour(),
    ]);

    $this->artisan('license-keys:expire')
        ->expectsOutputToContain('Expired 1 license keys.')
        ->assertSuccessful();

    expect($key->fresh()->status->value)->toBe('expired');
});

test('already expired keys are not touched', function (): void {
    $alreadyExpired = LicenseKey::factory()->expired()->create();
    $originalUpdatedAt = $alreadyExpired->updated_at;

    $this->artisan('license-keys:expire')
        ->expectsOutputToContain('Expired 0 license keys.')
        ->assertSuccessful();

    expect($alreadyExpired->fresh()->updated_at->equalTo($originalUpdatedAt))
        ->toBeTrue();
});
