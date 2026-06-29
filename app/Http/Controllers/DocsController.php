<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Docs\GetDocSlugsAction;
use Inertia\Inertia;
use Inertia\Response;

final class DocsController
{
    /**
     * Render a public documentation page. The slug is validated against the
     * navigation defined in config/docs.php so unknown URLs return a 404.
     */
    public function show(GetDocSlugsAction $getSlugs, ?string $slug = null): Response
    {
        /** @var array<int, array{title: string, items: array<int, array{slug: string, title: string}>}> $sections */
        $sections = config('docs.sections');

        $slugs = $getSlugs->handle($sections);
        $slug ??= $slugs->first();

        abort_unless($slugs->contains($slug), 404);

        return Inertia::render('docs/Show', [
            'slug' => $slug,
            'sections' => $sections,
            'canonical' => url('/guide/'.$slug),
        ]);
    }
}
