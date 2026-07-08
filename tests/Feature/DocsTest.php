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

test('the profiler guide slug renders', function (): void {
    get('/guide/profiler')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'profiler'),
        );
});

test('the python guide slug renders', function (): void {
    get('/guide/python')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'python'),
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

test('the ide guide slug renders', function (): void {
    get('/guide/ide')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'ide'),
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

test('the typesense guide slug renders', function (): void {
    get('/guide/typesense')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'typesense'),
        );
});

test('the database guide slugs render', function (string $slug): void {
    get('/guide/'.$slug)
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', $slug),
        );
})->with(['mariadb', 'mysql', 'postgresql', 'mongodb']);

test('the homebrew python path guide renders', function (): void {
    get('/guide/homebrew-python-path')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'homebrew-python-path'),
        );
});

test('the homebrew database path guide renders', function (): void {
    get('/guide/homebrew-database-path')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('docs/Show')
            ->where('slug', 'homebrew-database-path'),
        );
});

test('an unknown doc slug returns 404', function (): void {
    get('/guide/does-not-exist')->assertNotFound();
});

test('the old /docs path is not used by the guide', function (): void {
    get('/docs')->assertNotFound();
});

test('the sitemap lists every doc slug from the config', function (): void {
    $sitemap = file_get_contents(public_path('sitemap.xml'));

    $slugs = collect(config('docs.sections'))
        ->flatMap(static fn (array $section): array => $section['items'])
        ->map(static fn (array $item): string => $item['slug']);

    foreach ($slugs as $slug) {
        expect($sitemap)->toContain('/guide/'.$slug.'</loc>');
    }
});
