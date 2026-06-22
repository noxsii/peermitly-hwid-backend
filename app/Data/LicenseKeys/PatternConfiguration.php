<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

use App\Enums\LicenseKeyGeneratorType;

final readonly class PatternConfiguration extends LicenseKeyConfiguration
{
    public function __construct(
        public string $pattern,
        bool $excludeAmbiguousCharacters = true,
        bool $caseSensitive = false,
    ) {
        parent::__construct(
            prefix: '',
            suffix: '',
            prefixSeparator: '',
            suffixSeparator: '',
            excludeAmbiguousCharacters: $excludeAmbiguousCharacters,
            caseSensitive: $caseSensitive,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            pattern: (string) ($data['pattern'] ?? ''),
            excludeAmbiguousCharacters: (bool) ($data['exclude_ambiguous_characters'] ?? true),
            caseSensitive: (bool) ($data['case_sensitive'] ?? false),
        );
    }

    public function generatorType(): LicenseKeyGeneratorType
    {
        return LicenseKeyGeneratorType::PATTERN;
    }

    public function toArray(): array
    {
        return [
            'pattern' => $this->pattern,
            'exclude_ambiguous_characters' => $this->excludeAmbiguousCharacters,
            'case_sensitive' => $this->caseSensitive,
        ];
    }
}
