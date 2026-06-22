<?php

declare(strict_types=1);

test('landing page renders all sections without console errors', function (): void {
    $page = visit('/');

    $page->assertNoJavascriptErrors()
        ->assertSee('Spoof your HWID. Stay in control.')
        ->assertSee('HWID Spoofing')
        ->assertSee('Safe by design')
        ->assertSee('Instant apply');
});
