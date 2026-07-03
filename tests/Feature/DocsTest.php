<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('the docs index renders the first page', function (): void {
    get('/guide')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'setup')
            ->has('sections'),
        );
});

test('a known doc slug renders', function (): void {
    get('/guide/introduction')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'introduction'),
        );
});

test('the react guide slug renders', function (): void {
    get('/guide/react')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'react'),
        );
});

test('the astro guide slug renders', function (): void {
    get('/guide/astro')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'astro'),
        );
});

test('the debug guide slug renders', function (): void {
    get('/guide/debug')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'debug'),
        );
});

test('the meilisearch guide slug renders', function (): void {
    get('/guide/meilisearch')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'meilisearch'),
        );
});

test('the nuxt guide slug renders', function (): void {
    get('/guide/nuxt')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'nuxt'),
        );
});

test('the sidebar editor guide slug renders', function (): void {
    get('/guide/sidebar-editor')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'sidebar-editor'),
        );
});

test('an unknown doc slug returns 404', function (): void {
    get('/guide/does-not-exist')->assertNotFound();
});

test('the old /docs path is not used by the guide', function (): void {
    get('/docs')->assertNotFound();
});
