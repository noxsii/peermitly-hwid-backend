<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\AppReleaseResource;
use App\Models\AppRelease;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DownloadController
{
    public function index(): Response
    {
        return Inertia::render('downloads/Index', [
            'releases' => Inertia::defer(static fn () => AppReleaseResource::collection(
                AppRelease::query()
                    ->where('is_active', true)
                    ->orderByDesc('published_at')
                    ->orderByDesc('created_at')
                    ->get(),
            )),
        ]);
    }

    public function download(AppRelease $release): StreamedResponse
    {
        $disk = Storage::disk('local');

        abort_unless($disk->exists($release->file_path), 404);

        return $disk->download($release->file_path, $release->file_name);
    }
}
