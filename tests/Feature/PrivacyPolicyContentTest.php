<?php

declare(strict_types=1);

test('privacy policy explains HWID device binding', function (): void {
    $privacyPolicy = file_get_contents(resource_path('js/pages/privacy/Index.vue'));

    expect($privacyPolicy)->not->toBeFalse()
        ->and($privacyPolicy)->toContain('Device binding')
        ->and($privacyPolicy)->toContain('device identifier (HWID)')
        ->and($privacyPolicy)->toContain('Art. 6')
        ->and($privacyPolicy)->toContain('until your account is deleted')
        ->and($privacyPolicy)->not->toContain('We do not upload\n                    or store your hardware identifiers');
});
