<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Data\LicenseKeys\LicenseKeyGenerationContext;
use App\Data\LicenseKeys\LicenseKeyGenerationResult;
use App\Exceptions\LicenseKeyGenerationException;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Services\LicenseKeys\Generators\LicenseKeyGeneratorFactory;

final readonly class GenerateLicenseKeyAction
{
    private const int MAX_ATTEMPTS = 10;

    public function __construct(
        private LicenseKeyGeneratorFactory $factory,
        private NormalizeLicenseKeyAction $normalize,
        private BuildLicenseKeyConfigurationAction $buildConfiguration,
    ) {}

    public function handle(LicenseKeyType $type, LicenseKeyGenerationContext $context = new LicenseKeyGenerationContext()): LicenseKeyGenerationResult
    {
        $configuration = $this->buildConfiguration->handle($type);

        for ($attempt = 0; $attempt < self::MAX_ATTEMPTS; $attempt++) {
            $key = $this->factory->generate($configuration, $context);
            $normalized = $this->normalize->handle($key, $configuration->caseSensitive);

            $exists = LicenseKey::query()
                ->where('team_id', $type->team_id)
                ->where('normalized_key', $normalized)
                ->exists();

            if (! $exists) {
                return new LicenseKeyGenerationResult($key, $normalized);
            }
        }

        throw new LicenseKeyGenerationException(
            'Unable to generate a unique license key after '.self::MAX_ATTEMPTS.' attempts. Adjust the key type configuration to increase entropy.'
        );
    }
}
