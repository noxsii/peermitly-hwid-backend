<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

use App\Enums\LicenseKeyGeneratorType;

final readonly class RandomCharacterConfiguration extends LicenseKeyConfiguration
{
    public function __construct(
        public int $length,
        public int $groupLength,
        public string $separator,
        public bool $uppercase,
        public bool $lowercase,
        public bool $numbers,
        public bool $specialCharacters,
        public ?string $customCharset,
        string $prefix = '',
        string $suffix = '',
        string $prefixSeparator = '-',
        string $suffixSeparator = '-',
        bool $excludeAmbiguousCharacters = true,
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
            length: (int) ($data['length'] ?? 12),
            groupLength: (int) ($data['group_length'] ?? 4),
            separator: (string) ($data['separator'] ?? '-'),
            uppercase: (bool) ($data['uppercase'] ?? true),
            lowercase: (bool) ($data['lowercase'] ?? false),
            numbers: (bool) ($data['numbers'] ?? true),
            specialCharacters: (bool) ($data['special_characters'] ?? false),
            customCharset: isset($data['custom_charset']) && $data['custom_charset'] !== '' ? (string) $data['custom_charset'] : null,
            prefix: (string) ($data['prefix'] ?? ''),
            suffix: (string) ($data['suffix'] ?? ''),
            prefixSeparator: (string) ($data['prefix_separator'] ?? '-'),
            suffixSeparator: (string) ($data['suffix_separator'] ?? '-'),
            excludeAmbiguousCharacters: (bool) ($data['exclude_ambiguous_characters'] ?? true),
            caseSensitive: (bool) ($data['case_sensitive'] ?? false),
        );
    }

    public function generatorType(): LicenseKeyGeneratorType
    {
        return LicenseKeyGeneratorType::RANDOM;
    }

    public function toArray(): array
    {
        return [
            'length' => $this->length,
            'group_length' => $this->groupLength,
            'separator' => $this->separator,
            'uppercase' => $this->uppercase,
            'lowercase' => $this->lowercase,
            'numbers' => $this->numbers,
            'special_characters' => $this->specialCharacters,
            'custom_charset' => $this->customCharset,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'prefix_separator' => $this->prefixSeparator,
            'suffix_separator' => $this->suffixSeparator,
            'exclude_ambiguous_characters' => $this->excludeAmbiguousCharacters,
            'case_sensitive' => $this->caseSensitive,
        ];
    }
}
