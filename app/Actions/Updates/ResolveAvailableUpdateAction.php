<?php

declare(strict_types=1);

namespace App\Actions\Updates;

use App\Models\AppRelease;

final readonly class ResolveAvailableUpdateAction
{
    /**
     * Return the newest active release for the platform when it is strictly
     * newer than the client's current version, otherwise null (= up to date).
     */
    public function handle(string $platform, string $currentVersion): ?AppRelease
    {
        $latest = AppRelease::query()
            ->where('platform', $platform)
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->first();

        if (! $latest instanceof AppRelease) {
            return null;
        }

        if (version_compare($this->normalize($latest->version), $this->normalize($currentVersion), '<=')) {
            return null;
        }

        return $latest;
    }

    private function normalize(string $version): string
    {
        return mb_ltrim($version, 'vV');
    }
}
