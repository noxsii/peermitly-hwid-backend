<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Enums\LicenseKeyStatus;
use App\Enums\LicenseValidityUnit;
use App\Models\LicenseKey;
use App\Models\LicenseKeyActivation;
use Illuminate\Support\Facades\DB;

final class ActivateLicenseKeyAction
{
    public function handle(LicenseKey $licenseKey, ?string $machineId = null, ?string $ipAddress = null, ?string $userAgent = null): LicenseKey
    {
        return DB::transaction(function () use ($licenseKey, $machineId, $ipAddress, $userAgent): LicenseKey {
            /** @var LicenseKey $locked */
            $locked = LicenseKey::query()
                ->whereKey($licenseKey->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status === LicenseKeyStatus::PENDING && $locked->activated_at === null) {
                $activatedAt = now();
                $expiresAt = $locked->validity_unit === LicenseValidityUnit::LIFETIME
                    ? null
                    : $locked->validity_unit->applyTo($activatedAt, (int) $locked->validity_amount);

                $locked->forceFill([
                    'status' => LicenseKeyStatus::ACTIVE->value,
                    'activated_at' => $activatedAt,
                    'expires_at' => $expiresAt,
                ])->save();
            }

            if ($machineId !== null) {
                LicenseKeyActivation::query()->updateOrCreate(
                    ['license_key_id' => $locked->id, 'machine_id' => $machineId],
                    [
                        'team_id' => $locked->team_id,
                        'ip_address' => $ipAddress,
                        'user_agent' => $userAgent,
                        'activated_at' => $locked->activated_at ?? now(),
                        'last_seen_at' => now(),
                    ],
                );
            }

            return $locked->refresh();
        });
    }
}
