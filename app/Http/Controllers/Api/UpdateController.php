<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Updates\ResolveAvailableUpdateAction;
use App\Models\AppRelease;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class UpdateController
{
    /**
     * Tauri updater endpoint. Returns 204 when the client is up to date,
     * or 200 with the update manifest when a newer release exists.
     */
    public function check(
        ResolveAvailableUpdateAction $resolve,
        string $target,
        string $arch,
        string $current,
    ): JsonResponse|Response {
        $release = $resolve->handle("{$target}-{$arch}", $current);

        if (! $release instanceof AppRelease) {
            return response()->noContent();
        }

        return response()->json([
            'version' => $release->version,
            'pub_date' => $release->published_at?->toIso8601String(),
            'url' => route('api.update.download', ['version' => $release->version]),
            'signature' => $release->signature,
            'notes' => $release->notes,
        ]);
    }

    public function download(string $version): StreamedResponse
    {
        $release = AppRelease::query()
            ->where('version', $version)
            ->where('is_active', true)
            ->firstOrFail();

        $disk = Storage::disk('local');

        abort_unless($disk->exists($release->file_path), 404);

        return $disk->download($release->file_path, $release->file_name);
    }
}
