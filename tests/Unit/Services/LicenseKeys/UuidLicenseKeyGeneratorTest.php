<?php

declare(strict_types=1);
use App\Data\LicenseKeys\UuidConfiguration;
use App\Services\LicenseKeys\Generators\UuidLicenseKeyGenerator;
use Illuminate\Support\Sleep;

test('default config produces lowercase uuid with hyphens', function (): void {
    $key = new UuidLicenseKeyGenerator()->generate(
        new UuidConfiguration(uuidVersion: 'v4', withHyphens: true, uppercase: false),
    );

    expect($key)->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/');
});

test('without hyphens removes dashes', function (): void {
    $key = new UuidLicenseKeyGenerator()->generate(
        new UuidConfiguration(uuidVersion: 'v4', withHyphens: false, uppercase: false),
    );

    expect($key)->toMatch('/^[0-9a-f]{32}$/');
});

test('uppercase config returns uppercase uuid', function (): void {
    $key = new UuidLicenseKeyGenerator()->generate(
        new UuidConfiguration(uuidVersion: 'v4', withHyphens: true, uppercase: true),
    );

    expect($key)->toMatch('/^[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}$/');
});

test('prefix and suffix are applied with separators', function (): void {
    $key = new UuidLicenseKeyGenerator()->generate(
        new UuidConfiguration(
            uuidVersion: 'v4',
            withHyphens: true,
            uppercase: true,
            prefix: 'LIC',
            suffix: 'END',
        ),
    );

    expect($key)->toStartWith('LIC-')->toEndWith('-END');
});

test('uuid v7 produces ordered values', function (): void {
    $generator = new UuidLicenseKeyGenerator();
    $config = new UuidConfiguration(uuidVersion: 'v7', withHyphens: true, uppercase: false);

    $first = $generator->generate($config);
    Sleep::usleep(2000);
    $second = $generator->generate($config);

    expect(strcmp($second, $first))->toBeGreaterThan(0);
});
