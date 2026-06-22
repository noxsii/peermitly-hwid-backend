<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

use App\Enums\LicenseValidityUnit;
use Illuminate\Support\Carbon;

final readonly class BulkExtendCriteria
{
    public function __construct(
        public int $teamId,
        public Carbon $fromExpiresAt,
        public int $amount,
        public LicenseValidityUnit $unit,
    ) {}
}
