<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Enums\LicenseKeyStatus;
use App\Models\LicenseKey;

final class RestoreLicenseKeyAction
{
    public function handle(LicenseKey $licenseKey): LicenseKey
    {
        $status = match (true) {
            $licenseKey->activated_at === null => LicenseKeyStatus::PENDING,
            $licenseKey->expires_at !== null && $licenseKey->expires_at->isPast() => LicenseKeyStatus::EXPIRED,
            default => LicenseKeyStatus::ACTIVE,
        };

        $licenseKey->forceFill([
            'status' => $status->value,
            'revoked_at' => null,
            'revoked_reason' => null,
        ])->save();

        return $licenseKey->refresh();
    }
}
