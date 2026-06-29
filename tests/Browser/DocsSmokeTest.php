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
