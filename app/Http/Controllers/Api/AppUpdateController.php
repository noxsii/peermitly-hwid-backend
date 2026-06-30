<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\AppRelease;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

final class AppUpdateController
{
    /**
     * Tauri updater manifest. Returns the latest published release; the client
     * compares the version itself and only updates when newer. Responds 204
     * when no release is published yet.
     */
    public function latest(): JsonResponse|Response
    {
        $release = AppRelease::query()
            ->where('is_published', true)
            ->latest('published_at')
            ->orderByDesc('id')
            ->first();

        if (! $release instanceof AppRelease) {
            return response()->noContent();
        }

        $platforms = [];

        foreach ($release->platforms as $entry) {
            $platforms[$entry['platform']] = [
                'signature' => $entry['signature'],
                'url' => url(Storage::disk('public')->url($entry['path'])),
            ];
        }

        return response()->json([
            'version' => $release->version,
            'notes' => $release->notes ?? '',
            'pub_date' => ($release->published_at ?? $release->created_at)?->toIso8601String(),
            'platforms' => $platforms,
        ]);
    }
}
