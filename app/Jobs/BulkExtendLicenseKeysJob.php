<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\LicenseKeys\BulkExtendLicenseKeysAction;
use App\Data\LicenseKeys\BulkExtendCriteria;
use App\Enums\LicenseValidityUnit;
use App\Models\User;
use App\Notifications\LicenseKeysBulkExtended;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Support\Facades\Date;

#[Timeout(600)]
final class BulkExtendLicenseKeysJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $teamId,
        public readonly string $fromExpiresAtIso,
        public readonly int $amount,
        public readonly string $unit,
        public readonly int $createdById,
    ) {}

    public function handle(BulkExtendLicenseKeysAction $action): void
    {
        $unit = LicenseValidityUnit::from($this->unit);

        $criteria = new BulkExtendCriteria(
            teamId: $this->teamId,
            fromExpiresAt: Date::parse($this->fromExpiresAtIso),
            amount: $this->amount,
            unit: $unit,
        );

        $count = $action->handle($criteria);

        $user = User::query()->findOrFail($this->createdById);
        $user->notify(new LicenseKeysBulkExtended(
            count: $count,
            amount: $this->amount,
            unit: $unit,
        ));
    }
}
