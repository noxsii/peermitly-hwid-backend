<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Data\LicenseKeys\LicenseKeyConfiguration;
use App\Models\LicenseKeyType;

final readonly class BuildLicenseKeyConfigurationAction
{
    public function handle(LicenseKeyType $type): LicenseKeyConfiguration
    {
        /** @var array<string, mixed> $configuration */
        $configuration = $type->getAttribute('configuration') ?? [];

        return LicenseKeyConfiguration::from($type->generator_type, $configuration);
    }
}
