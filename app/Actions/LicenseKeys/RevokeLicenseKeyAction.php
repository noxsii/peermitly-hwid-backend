<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Enums\LicenseKeyStatus;
use App\Models\LicenseKey;

final class RevokeLicenseKeyAction
{
    public function handle(LicenseKey $licenseKey, string $reason): LicenseKey
    {
        $licenseKey->forceFill([
            'status' => LicenseKeyStatus::REVOKED->value,
            'revoked_at' => now(),
            'revoked_reason' => $reason,
        ])->save();

        return $licenseKey->refresh();
    }
}
