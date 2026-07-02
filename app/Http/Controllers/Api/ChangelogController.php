<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ChangelogResource;
use App\Models\Changelog;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ChangelogController
{
    /**
     * Return every published changelog entry, newest first.
     */
    public function index(): AnonymousResourceCollection
    {
        $changelogs = Changelog::query()
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->orderByDesc('id')
            ->cursorPaginate(20);

        return ChangelogResource::collection($changelogs);
    }
}
