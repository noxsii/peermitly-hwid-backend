<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Data\LicenseKeys\BulkExtendCriteria;
use App\Enums\LicenseValidityUnit;
use App\Models\LicenseKey;

final readonly class BulkExtendLicenseKeysAction
{
    public function __construct(
        private ExtendLicenseKeyAction $extend,
    ) {}

    /**
     * Extends every team-scoped license key whose expires_at is on or after
     * the given threshold. Lifetime keys are ignored (nothing to extend).
     *
     * Returns the number of keys that were extended.
     */
    public function handle(BulkExtendCriteria $criteria): int
    {
        $count = 0;

        LicenseKey::query()
            ->where('team_id', $criteria->teamId)
            ->whereNotNull('expires_at')
            ->where('expires_at', '>=', $criteria->fromExpiresAt)
            ->where('validity_unit', '!=', LicenseValidityUnit::LIFETIME->value)
            ->chunkById(100, function ($keys) use ($criteria, &$count): void {
                foreach ($keys as $key) {
                    $this->extend->handle($key, $criteria->amount, $criteria->unit);
                    $count++;
                }
            });

        return $count;
    }
}
