<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

final readonly class LicenseKeyGenerationResult
{
    public function __construct(
        public string $key,
        public string $normalizedKey,
    ) {}
}
