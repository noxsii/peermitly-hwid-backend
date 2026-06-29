<?php

declare(strict_types=1);

test('landing page renders all sections without console errors', function (): void {
    $page = visit('/');

    $page->assertNoJavascriptErrors()
        ->assertSee('Your whole dev stack.')
        ->assertSee('Instant sites')
        ->assertSee('How it works')
        ->assertSee('Questions, answered');
});
