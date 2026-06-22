<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ChangelogResource;
use App\Models\Changelog;
use Inertia\Inertia;
use Inertia\Response;

final class ChangelogController
{
    public function index(): Response
    {
        return Inertia::render('changelog/Index', [
            'entries' => Inertia::defer(static fn () => ChangelogResource::collection(
                Changelog::query()
                    ->whereNotNull('published_at')
                    ->latest('published_at')
                    ->paginate(10)
                    ->withQueryString(),
            )),
        ]);
    }
}
