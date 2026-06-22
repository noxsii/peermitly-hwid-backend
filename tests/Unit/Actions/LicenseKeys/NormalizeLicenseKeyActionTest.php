<?php

declare(strict_types=1);

use App\Actions\LicenseKeys\NormalizeLicenseKeyAction;

test('case insensitive normalisation strips separators and uppercases', function (): void {
    $result = new NormalizeLicenseKeyAction()->handle('  lic-k7dm-q9ra-x4tp  ', false);

    expect($result)->toBe('LICK7DMQ9RAX4TP');
});

test('case sensitive normalisation strips separators but keeps case', function (): void {
    $result = new NormalizeLicenseKeyAction()->handle('api-a9Kd-p7Qm', true);

    expect($result)->toBe('apia9Kdp7Qm');
});

test('whitespace variants normalise to same value', function (): void {
    $action = new NormalizeLicenseKeyAction();

    expect($action->handle('LIC-K7DM-Q9RA-X4TP', false))
        ->toBe($action->handle('lic k7dm q9ra x4tp', false))
        ->toBe($action->handle('LICK7DMQ9RAX4TP', false));
});

test('zero-width characters are removed', function (): void {
    $result = new NormalizeLicenseKeyAction()->handle("LIC\u{200B}K7DM", false);

    expect($result)->toBe('LICK7DM');
});
