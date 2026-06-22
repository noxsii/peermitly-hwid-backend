<?php

declare(strict_types=1);

use App\Models\Changelog;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('changelog page requires authentication', function (): void {
    $this->get('/changelog')->assertRedirect('/login');
});

test('changelog page renders for authenticated users', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/changelog')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page->component('changelog/Index'),
        );
});

test('changelog route is named changelog.index', function (): void {
    expect(route('changelog.index', absolute: false))->toBe('/changelog');
});

test('unpublished changelogs are hidden from the public list', function (): void {
    Changelog::factory()->unpublished()->create();
    Changelog::factory()->create(['title' => 'Published one']);

    $visible = Changelog::query()
        ->whereNotNull('published_at')
        ->latest('published_at')
        ->pluck('title')
        ->all();

    expect($visible)->toBe(['Published one']);
});
