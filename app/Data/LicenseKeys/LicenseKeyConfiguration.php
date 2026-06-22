<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

use App\Enums\LicenseKeyGeneratorType;

abstract readonly class LicenseKeyConfiguration
{
    public function __construct(
        public string $prefix,
        public string $suffix,
        public string $prefixSeparator,
        public string $suffixSeparator,
        public bool $excludeAmbiguousCharacters,
        public bool $caseSensitive,
    ) {}

    abstract public function generatorType(): LicenseKeyGeneratorType;

    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    /**
     * @param  array<string, mixed>  $data
     */
    final public static function from(LicenseKeyGeneratorType $type, array $data): self
    {
        return match ($type) {
            LicenseKeyGeneratorType::UUID => UuidConfiguration::fromArray($data),
            LicenseKeyGeneratorType::RANDOM => RandomCharacterConfiguration::fromArray($data),
            LicenseKeyGeneratorType::PATTERN => PatternConfiguration::fromArray($data),
        };
    }
}
