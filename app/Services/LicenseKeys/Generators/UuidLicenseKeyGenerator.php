<?php

declare(strict_types=1);

namespace App\Services\LicenseKeys\Generators;

use App\Data\LicenseKeys\UuidConfiguration;
use Illuminate\Support\Str;

final class UuidLicenseKeyGenerator
{
    public function generate(UuidConfiguration $configuration): string
    {
        $uuid = $configuration->uuidVersion === 'v7'
            ? (string) Str::uuid7()
            : (string) Str::uuid();

        if (! $configuration->withHyphens) {
            $uuid = str_replace('-', '', $uuid);
        }

        if ($configuration->uppercase) {
            $uuid = mb_strtoupper($uuid);
        }

        if ($configuration->prefix !== '') {
            $uuid = $configuration->prefix.$configuration->prefixSeparator.$uuid;
        }

        if ($configuration->suffix !== '') {
            $uuid .= $configuration->suffixSeparator.$configuration->suffix;
        }

        return $uuid;
    }
}
