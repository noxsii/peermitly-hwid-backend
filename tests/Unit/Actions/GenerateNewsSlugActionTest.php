<?php

declare(strict_types=1);

use App\Actions\News\GenerateNewsSlugAction;
use App\Models\News;

test('it slugifies the title', function (): void {
    $slug = resolve(GenerateNewsSlugAction::class)->handle('Hello World News');

    expect($slug)->toBe('hello-world-news');
});

test('it appends a suffix on collision', function (): void {
    News::factory()->create(['slug' => 'launch-day']);

    $slug = resolve(GenerateNewsSlugAction::class)->handle('Launch Day');

    expect($slug)->toBe('launch-day-2');
});

test('it ignores the current record when checking uniqueness', function (): void {
    $news = News::factory()->create(['slug' => 'launch-day']);

    $slug = resolve(GenerateNewsSlugAction::class)->handle('Launch Day', $news->id);

    expect($slug)->toBe('launch-day');
});

test('it falls back when the title has no sluggable characters', function (): void {
    $slug = resolve(GenerateNewsSlugAction::class)->handle('...');

    expect($slug)->toBe('news');
});
