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

test('the Grav CMS guide renders with its sidebar entry', function (): void {
    $page = visit('/guide/grav');

    $page->assertNoJavascriptErrors()
        ->assertSee('Grav CMS')
        ->assertSee('flat-file')
        ->assertSee('On this page');
});

test('a guide page remains usable on mobile', function (): void {
    $page = visit('/guide/introduction')->on()->mobile();

    $page->assertNoJavascriptErrors()
        ->assertSee('Introduction')
        ->assertVisible('[aria-label="Open navigation"]');
});

test('a guide image opens in a zoom overlay and closes again', function (): void {
    $page = visit('/guide/php');

    $page->assertNoJavascriptErrors()
        ->click('.docs-prose img')
        ->assertVisible('[role="dialog"] img')
        ->click('[aria-label="Close"]')
        ->assertMissing('[role="dialog"]');
});

test('the copy button stays fixed while its code block scrolls', function (): void {
    $page = visit('/guide/php');

    $page->assertNoJavascriptErrors()->assertScript(
        <<<'JS'
        (() => {
            const pre = document.querySelector('.docs-code-block pre');
            const button = document.querySelector('.docs-copy-btn');
            const initialRight = button.getBoundingClientRect().right;

            pre.scrollLeft = pre.scrollWidth;

            return Math.abs(button.getBoundingClientRect().right - initialRight) < 1;
        })()
        JS,
        true,
    );
});
