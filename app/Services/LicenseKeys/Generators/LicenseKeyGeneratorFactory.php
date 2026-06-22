<?php

declare(strict_types=1);

namespace App\Services\LicenseKeys\Generators;

use App\Data\LicenseKeys\LicenseKeyConfiguration;
use App\Data\LicenseKeys\LicenseKeyGenerationContext;
use App\Data\LicenseKeys\PatternConfiguration;
use App\Data\LicenseKeys\RandomCharacterConfiguration;
use App\Data\LicenseKeys\UuidConfiguration;
use LogicException;

final readonly class LicenseKeyGeneratorFactory
{
    public function __construct(
        private UuidLicenseKeyGenerator $uuid,
        private RandomCharacterLicenseKeyGenerator $random,
        private PatternLicenseKeyGenerator $pattern,
    ) {}

    public function generate(LicenseKeyConfiguration $configuration, LicenseKeyGenerationContext $context): string
    {
        return match (true) {
            $configuration instanceof UuidConfiguration => $this->uuid->generate($configuration),
            $configuration instanceof RandomCharacterConfiguration => $this->random->generate($configuration),
            $configuration instanceof PatternConfiguration => $this->pattern->generate($configuration, $context),
            default => throw new LogicException('Unsupported LicenseKeyConfiguration: '.$configuration::class),
        };
    }
}
