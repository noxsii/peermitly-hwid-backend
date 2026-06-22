<?php

declare(strict_types=1);

test('landing page renders all sections without console errors', function (): void {
    $page = visit('/');

    $page->assertNoJavascriptErrors()
        ->assertSee('How it works')
        ->assertSee('One endpoint. Token-secured.')
        ->assertSee('Desktop apps')
        ->assertSee('Questions, answered.');
});
