<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Enums\LicenseKeyStatus;
use App\Enums\LicenseValidityUnit;
use App\Models\LicenseKey;

final class ExtendLicenseKeyAction
{
    public function handle(LicenseKey $licenseKey, int $amount, LicenseValidityUnit $unit): LicenseKey
    {
        if ($licenseKey->status === LicenseKeyStatus::PENDING) {
            $licenseKey->forceFill([
                'validity_amount' => $unit->isLifetime() ? null : $amount,
                'validity_unit' => $unit->value,
            ])->save();

            return $licenseKey->refresh();
        }

        if ($unit->isLifetime()) {
            $licenseKey->forceFill([
                'validity_unit' => LicenseValidityUnit::LIFETIME->value,
                'validity_amount' => null,
                'expires_at' => null,
                'status' => LicenseKeyStatus::ACTIVE->value,
            ])->save();

            return $licenseKey->refresh();
        }

        $base = $licenseKey->status === LicenseKeyStatus::EXPIRED ? now() : ($licenseKey->expires_at ?? now());
        $newExpires = $unit->applyTo($base, $amount);

        $licenseKey->forceFill([
            'expires_at' => $newExpires,
            'status' => LicenseKeyStatus::ACTIVE->value,
            'validity_amount' => $amount,
            'validity_unit' => $unit->value,
        ])->save();

        return $licenseKey->refresh();
    }
}
