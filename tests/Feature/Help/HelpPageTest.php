<?php

declare(strict_types=1);

use App\Models\HelpArticle;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('help index requires authentication', function (): void {
    $this->get('/help')->assertRedirect('/login');
});

test('help index renders for authenticated user', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/help')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page->component('help/Index'),
        );
});

test('help index route is named help.index', function (): void {
    expect(route('help.index', absolute: false))->toBe('/help');
});

test('only published articles are visible in the public listing', function (): void {
    HelpArticle::factory()->unpublished()->create(['title' => 'Draft article']);
    HelpArticle::factory()->create(['title' => 'Live article']);

    $visible = HelpArticle::query()
        ->whereNotNull('published_at')
        ->pluck('title')
        ->all();

    expect($visible)->toBe(['Live article']);
});

test('show route renders the article', function (): void {
    $article = HelpArticle::factory()->create([
        'title' => 'Getting started',
        'slug' => 'getting-started',
    ]);

    $this->actingAs(User::factory()->create())
        ->get('/help/'.$article->slug)
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->component('help/Show')
                ->where('article.data.title', 'Getting started')
                ->where('article.data.slug', 'getting-started'),
        );
});

test('show route returns 404 for unpublished article', function (): void {
    $article = HelpArticle::factory()->unpublished()->create(['slug' => 'secret']);

    $this->actingAs(User::factory()->create())
        ->get('/help/'.$article->slug)
        ->assertNotFound();
});
