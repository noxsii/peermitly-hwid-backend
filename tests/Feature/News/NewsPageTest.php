<?php

declare(strict_types=1);

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;

test('deleting a news article removes its uploaded image from disk', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('news/cover.jpg', 'binary');

    $news = News::factory()->create(['image_path' => 'news/cover.jpg']);

    $news->delete();

    Storage::disk('public')->assertMissing('news/cover.jpg');
});

test('deleting a news article without an image does not error', function (): void {
    Storage::fake('public');

    $news = News::factory()->withoutImage()->create();

    $news->delete();

    expect(News::query()->whereKey($news->getKey())->exists())->toBeFalse();
});

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
