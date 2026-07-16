<?php

declare(strict_types=1);

use App\Models\News;
use Inertia\Testing\AssertableInertia;

test('news index is public and lists only published entries', function (): void {
    News::factory()->create(['title' => 'Published one']);
    News::factory()->unpublished()->create(['title' => 'Draft']);

    $this->get('/news')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->component('news/Index', false)
                ->has('entries.data', 1)
                ->where('entries.data.0.title', 'Published one'),
        );
});

test('news index is cursor paginated at 12', function (): void {
    News::factory()->count(15)->create();

    $this->get('/news')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page->has('entries.data', 12),
        );
});

test('news show returns a published article with content', function (): void {
    $news = News::factory()->create(['slug' => 'launch-day', 'title' => 'Launch Day']);

    $this->get('/news/launch-day')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->component('news/Show', false)
                ->where('article.title', 'Launch Day')
                ->where('article.content', $news->content)
                ->has('url'),
        );
});

test('news show 404s for a draft', function (): void {
    News::factory()->unpublished()->create(['slug' => 'secret']);

    $this->get('/news/secret')->assertNotFound();
});

test('news show 404s for an unknown slug', function (): void {
    $this->get('/news/does-not-exist')->assertNotFound();
});

test('news show exposes an absolute image url when an image exists', function (): void {
    News::factory()->create(['slug' => 'with-image', 'image_path' => 'news/x.jpg']);

    $this->get('/news/with-image')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->where('article.image_url', fn (?string $url): bool => is_string($url) && str_contains($url, 'news/x.jpg')),
        );
});

test('news route is named news.index', function (): void {
    expect(route('news.index', absolute: false))->toBe('/news');
});
