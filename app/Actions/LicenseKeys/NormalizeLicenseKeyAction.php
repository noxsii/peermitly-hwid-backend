<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

final class NormalizeLicenseKeyAction
{
    public function handle(string $raw, bool $caseSensitive): string
    {
        $normalized = preg_replace('/[\s\-_]+/u', '', mb_trim($raw)) ?? '';
        $normalized = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $normalized) ?? $normalized;

        return $caseSensitive ? $normalized : mb_strtoupper($normalized);
    }
}
