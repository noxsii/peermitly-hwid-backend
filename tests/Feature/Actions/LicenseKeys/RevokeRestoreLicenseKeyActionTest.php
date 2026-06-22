<?php

declare(strict_types=1);

use App\Actions\LicenseKeys\RestoreLicenseKeyAction;
use App\Actions\LicenseKeys\RevokeLicenseKeyAction;
use App\Models\LicenseKey;

test('revoke sets status, reason, and timestamp', function (): void {
    $key = LicenseKey::factory()->active()->create();

    $revoked = new RevokeLicenseKeyAction()->handle($key, 'Customer cancelled');

    expect($revoked->status->value)->toBe('revoked');
    expect($revoked->revoked_at)->not->toBeNull();
    expect($revoked->revoked_reason)->toBe('Customer cancelled');
});

test('restore brings revoked active key back to active', function (): void {
    $key = LicenseKey::factory()->revoked()->create();

    $restored = new RestoreLicenseKeyAction()->handle($key);

    expect($restored->status->value)->toBe('active');
    expect($restored->revoked_at)->toBeNull();
    expect($restored->revoked_reason)->toBeNull();
});

test('restore picks expired status when expires_at is past', function (): void {
    $key = LicenseKey::factory()->create([
        'status' => 'revoked',
        'activated_at' => now()->subYears(2),
        'expires_at' => now()->subMonth(),
        'revoked_at' => now()->subDay(),
        'revoked_reason' => 'foo',
    ]);

    $restored = new RestoreLicenseKeyAction()->handle($key);

    expect($restored->status->value)->toBe('expired');
});

test('restore picks pending status for never-activated key', function (): void {
    $key = LicenseKey::factory()->create([
        'status' => 'revoked',
        'activated_at' => null,
        'expires_at' => null,
        'revoked_at' => now(),
        'revoked_reason' => 'foo',
    ]);

    $restored = new RestoreLicenseKeyAction()->handle($key);

    expect($restored->status->value)->toBe('pending');
});
