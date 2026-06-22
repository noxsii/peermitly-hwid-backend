<?php

declare(strict_types=1);

use App\Data\LicenseKeys\LicenseKeyGenerationContext;
use App\Data\LicenseKeys\PatternConfiguration;
use App\Services\LicenseKeys\Generators\PatternLicenseKeyGenerator;

test('pattern with {XXXX} expands to 4-char alphanumeric blocks', function (): void {
    $key = new PatternLicenseKeyGenerator()->generate(
        new PatternConfiguration(pattern: 'LIC-{XXXX}-{XXXX}-{XXXX}'),
        LicenseKeyGenerationContext::empty(),
    );

    expect($key)->toMatch('/^LIC-[A-HJ-NP-Z3-79]{4}-[A-HJ-NP-Z3-79]{4}-[A-HJ-NP-Z3-79]{4}$/');
});

test('pattern resolves uuid placeholder', function (): void {
    $key = new PatternLicenseKeyGenerator()->generate(
        new PatternConfiguration(pattern: 'API-{uuid}'),
        LicenseKeyGenerationContext::empty(),
    );

    expect($key)->toStartWith('API-')->toMatch('/^API-[0-9a-f]{8}-/');
});

test('pattern resolves year and product context', function (): void {
    $key = new PatternLicenseKeyGenerator()->generate(
        new PatternConfiguration(pattern: '{product}-{year}-{XXXX}'),
        new LicenseKeyGenerationContext(productSlug: 'OFFICE', year: 2026),
    );

    expect($key)->toStartWith('OFFICE-2026-');
});

test('pattern with empty pattern throws', function (): void {
    expect(fn (): string => new PatternLicenseKeyGenerator()->generate(
        new PatternConfiguration(pattern: ''),
        LicenseKeyGenerationContext::empty(),
    ))->toThrow(InvalidArgumentException::class);
});
