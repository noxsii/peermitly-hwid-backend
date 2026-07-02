<?php

declare(strict_types=1);

use App\Models\Changelog;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('it returns published changelogs newest first', function (): void {
    $older = Changelog::factory()->create([
        'title' => 'Older',
        'version' => '1.0.0',
        'published_at' => now()->subDays(5),
    ]);
    $newer = Changelog::factory()->create([
        'title' => 'Newer',
        'version' => '1.1.0',
        'published_at' => now()->subDay(),
    ]);
    Changelog::factory()->create(['title' => 'Draft', 'published_at' => null]);

    Sanctum::actingAs(User::factory()->create(), ['app:use']);

    $this->getJson('/api/changelogs')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.title', $newer->title)
        ->assertJsonPath('data.1.title', $older->title)
        ->assertJsonStructure([
            'data' => [['uuid', 'title', 'version', 'content', 'published_at']],
            'meta' => ['next_cursor', 'prev_cursor', 'per_page'],
        ]);
});

test('it excludes unpublished changelogs', function (): void {
    Changelog::factory()->create(['published_at' => null]);

    Sanctum::actingAs(User::factory()->create(), ['app:use']);

    $this->getJson('/api/changelogs')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

test('the endpoint requires authentication', function (): void {
    $this->getJson('/api/changelogs')->assertUnauthorized();
});
