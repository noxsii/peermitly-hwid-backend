<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\HelpArticleResource;
use App\Models\HelpArticle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class HelpController
{
    public function index(Request $request): Response
    {
        $query = $request->string('q')->toString();

        return Inertia::render('help/Index', [
            'filters' => [
                'q' => $query,
            ],
            'articles' => Inertia::defer(static function () use ($query) {
                $builder = $query !== ''
                    ? HelpArticle::search($query)
                        ->query(fn ($eloquent) => $eloquent->whereNotNull('published_at'))
                    : HelpArticle::query()->whereNotNull('published_at')->latest('published_at');

                return HelpArticleResource::collection(
                    $builder->paginate(20)->withQueryString(),
                );
            }),
        ]);
    }

    public function show(HelpArticle $article): Response
    {
        abort_if($article->published_at === null, 404);

        return Inertia::render('help/Show', [
            'article' => HelpArticleResource::make($article),
        ]);
    }
}
