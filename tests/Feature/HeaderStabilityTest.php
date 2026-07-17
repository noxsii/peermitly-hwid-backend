<?php

declare(strict_types=1);

test('landing and docs headers use stable opaque surfaces', function (): void {
    $landingNav = file_get_contents(resource_path('js/components/landing/LandingNav.vue'));
    $docsLayout = file_get_contents(resource_path('js/components/docs/DocsLayout.vue'));

    expect($landingNav)->not->toBeFalse()
        ->and($docsLayout)->not->toBeFalse()
        ->and($landingNav)->not->toContain('useWindowScroll')
        ->and($landingNav)->not->toContain('backdrop-blur-md')
        ->and($landingNav)->toContain('bg-background fixed')
        ->and($docsLayout)->not->toContain('border-b backdrop-blur')
        ->and($docsLayout)->toContain('bg-background sticky');
});
