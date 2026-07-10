<?php

declare(strict_types=1);

test('a guide page renders its content, sidebar and table of contents', function (): void {
    $page = visit('/guide/introduction');

    $page->assertNoJavascriptErrors()
        ->assertSee('Introduction')
        ->assertSee('Why Peermitly')
        ->assertSee('On this page')
        ->assertSee('Getting Started');
});

test('a guide image opens in a zoom overlay and closes again', function (): void {
    $page = visit('/guide/php');

    $page->assertNoJavascriptErrors()
        ->click('.docs-prose img')
        ->assertVisible('[role="dialog"] img')
        ->click('[aria-label="Close"]')
        ->assertMissing('[role="dialog"]');
});
