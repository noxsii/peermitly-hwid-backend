<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia;

test('imprint route responds with 200 for guests', function (): void {
    $this->get('/imprint')->assertOk();
});

test('imprint route renders imprint/Index inertia component', function (): void {
    $this->get('/imprint')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('imprint/Index'));
});

test('imprint route is named imprint', function (): void {
    expect(route('imprint', absolute: false))->toBe('/imprint');
});