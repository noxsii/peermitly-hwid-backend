<?php

declare(strict_types=1);

use App\Models\Changelog;
use Inertia\Testing\AssertableInertia;

test('changelog page is public', function (): void {
    $this->get('/changelog')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page->component('changelog/Index'),
        );
});

test('changelog route is named changelog.index', function (): void {
    expect(route('changelog.index', absolute: false))->toBe('/changelog');
});

test('changelog page lists published entries', function (): void {
    Changelog::factory()->create(['title' => 'Published one']);

    $this->get('/changelog')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->component('changelog/Index')
                ->has('entries.data', 1)
                ->where('entries.data.0.title', 'Published one'),
        );
});

test('unpublished changelogs are hidden from the public list', function (): void {
    Changelog::factory()->unpublished()->create(['title' => 'Draft']);
    Changelog::factory()->create(['title' => 'Published one']);

    $this->get('/changelog')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->has('entries.data', 1)
                ->where('entries.data.0.title', 'Published one'),
        );
});

test('changelog entries are cursor paginated', function (): void {
    Changelog::factory()->count(15)->create();

    $this->get('/changelog')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page->has('entries.data', 10),
        );
});
