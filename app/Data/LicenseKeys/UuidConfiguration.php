<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

use App\Enums\LicenseKeyGeneratorType;

final readonly class UuidConfiguration extends LicenseKeyConfiguration
{
    public function __construct(
        public string $uuidVersion,
        public bool $withHyphens,
        public bool $uppercase,
        string $prefix = '',
        string $suffix = '',
        string $prefixSeparator = '-',
        string $suffixSeparator = '-',
        bool $excludeAmbiguousCharacters = false,
        bool $caseSensitive = false,
    ) {
        parent::__construct(
            $prefix,
            $suffix,
            $prefixSeparator,
            $suffixSeparator,
            $excludeAmbiguousCharacters,
            $caseSensitive,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            uuidVersion: (string) ($data['uuid_version'] ?? 'v4'),
            withHyphens: (bool) ($data['with_hyphens'] ?? true),
            uppercase: (bool) ($data['uppercase'] ?? false),
            prefix: (string) ($data['prefix'] ?? ''),
            suffix: (string) ($data['suffix'] ?? ''),
            prefixSeparator: (string) ($data['prefix_separator'] ?? '-'),
            suffixSeparator: (string) ($data['suffix_separator'] ?? '-'),
            excludeAmbiguousCharacters: (bool) ($data['exclude_ambiguous_characters'] ?? false),
            caseSensitive: (bool) ($data['case_sensitive'] ?? false),
        );
    }

    public function generatorType(): LicenseKeyGeneratorType
    {
        return LicenseKeyGeneratorType::UUID;
    }

    public function toArray(): array
    {
        return [
            'uuid_version' => $this->uuidVersion,
            'with_hyphens' => $this->withHyphens,
            'uppercase' => $this->uppercase,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'prefix_separator' => $this->prefixSeparator,
            'suffix_separator' => $this->suffixSeparator,
            'exclude_ambiguous_characters' => $this->excludeAmbiguousCharacters,
            'case_sensitive' => $this->caseSensitive,
        ];
    }
}
