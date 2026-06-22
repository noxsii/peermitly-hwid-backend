<?php

declare(strict_types=1);
use App\Data\LicenseKeys\RandomCharacterConfiguration;
use App\Services\LicenseKeys\Generators\RandomCharacterLicenseKeyGenerator;

function randomConfig(array $overrides = []): RandomCharacterConfiguration
{
    return new RandomCharacterConfiguration(
        length: $overrides['length'] ?? 12,
        groupLength: $overrides['group_length'] ?? 4,
        separator: $overrides['separator'] ?? '-',
        uppercase: $overrides['uppercase'] ?? true,
        lowercase: $overrides['lowercase'] ?? false,
        numbers: $overrides['numbers'] ?? true,
        specialCharacters: $overrides['special_characters'] ?? false,
        customCharset: $overrides['custom_charset'] ?? null,
        prefix: $overrides['prefix'] ?? '',
        suffix: $overrides['suffix'] ?? '',
        excludeAmbiguousCharacters: $overrides['exclude_ambiguous_characters'] ?? true,
        caseSensitive: $overrides['case_sensitive'] ?? false,
    );
}

test('uppercase numbers grouped key matches standard format', function (): void {
    $key = new RandomCharacterLicenseKeyGenerator()->generate(
        randomConfig(['prefix' => 'LIC']),
    );

    expect($key)->toMatch('/^LIC-[A-HJ-NP-Z3-79]{4}-[A-HJ-NP-Z3-79]{4}-[A-HJ-NP-Z3-79]{4}$/');
});

test('exclude_ambiguous_characters removes confusing chars', function (): void {
    $key = new RandomCharacterLicenseKeyGenerator()->generate(
        randomConfig(['length' => 200, 'group_length' => 0, 'separator' => '']),
    );

    expect($key)->not->toContain('0')
        ->not->toContain('O')
        ->not->toContain('1')
        ->not->toContain('I');
});

test('custom charset overrides classes', function (): void {
    $key = new RandomCharacterLicenseKeyGenerator()->generate(
        randomConfig([
            'length' => 50,
            'group_length' => 0,
            'separator' => '',
            'custom_charset' => 'ABCD',
            'exclude_ambiguous_characters' => false,
        ]),
    );

    expect($key)->toMatch('/^[ABCD]+$/')->toHaveLength(50);
});

test('empty charset throws', function (): void {
    expect(fn (): string => new RandomCharacterLicenseKeyGenerator()->generate(
        randomConfig([
            'length' => 10,
            'uppercase' => false,
            'lowercase' => false,
            'numbers' => false,
            'special_characters' => false,
            'exclude_ambiguous_characters' => false,
        ]),
    ))->toThrow(InvalidArgumentException::class);
});

test('suffix is appended with separator', function (): void {
    $key = new RandomCharacterLicenseKeyGenerator()->generate(
        randomConfig(['length' => 8, 'group_length' => 4, 'separator' => '-', 'suffix' => 'END']),
    );

    expect($key)->toEndWith('-END');
});
