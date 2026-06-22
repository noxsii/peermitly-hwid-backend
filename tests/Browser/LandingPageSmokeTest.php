<?php

declare(strict_types=1);

test('landing page renders all sections without console errors', function (): void {
    $page = visit('/');

    $page->assertNoJavascriptErrors()
        ->assertSee('Stay invisible.')
        ->assertSee('Truly undetectable')
        ->assertSee('How it works')
        ->assertSee('Questions, answered');
});
