<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\LicenseKeyStatus;
use App\Models\LicenseKey;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('license-keys:expire')]
#[Description('Mark active license keys with a past expiry as expired.')]
final class ExpireLicenseKeys extends Command
{
    public function handle(): int
    {
        $count = LicenseKey::query()
            ->where('status', LicenseKeyStatus::ACTIVE->value)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => LicenseKeyStatus::EXPIRED->value]);

        $this->info("Expired {$count} license keys.");

        return self::SUCCESS;
    }
}
