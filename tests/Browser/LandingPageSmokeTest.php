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

test('mobile landing page exposes navigation and news', function (): void {
    $page = visit('/')->on()->mobile();

    $page->assertNoJavascriptErrors()
        ->click('[aria-label="Open menu"]')
        ->assertVisible('[aria-label="Mobile navigation"]')
        ->assertSee('News')
        ->assertSee('Member login');
});
