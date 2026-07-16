<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Models\News;
use Inertia\Inertia;
use Inertia\Response;

final class NewsController
{
    public function index(): Response
    {
        return Inertia::render('news/Index', [
            'entries' => Inertia::scroll(static fn () => NewsResource::collection(
                News::query()
                    ->whereNotNull('published_at')
                    ->latest('published_at')
                    ->orderByDesc('id')
                    ->cursorPaginate(12),
            )),
        ]);
    }

    public function show(News $news): Response
    {
        abort_if($news->published_at === null, 404);

        return Inertia::render('news/Show', [
            'article' => new NewsResource($news),
            'url' => route('news.show', $news),
        ]);
    }
}
