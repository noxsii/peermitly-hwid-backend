<?php

declare(strict_types=1);

namespace App\Services\LicenseKeys\Generators;

use App\Data\LicenseKeys\LicenseKeyGenerationContext;
use App\Data\LicenseKeys\PatternConfiguration;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class PatternLicenseKeyGenerator
{
    private const string UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private const string LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';

    private const string NUMBERS = '0123456789';

    private const string AMBIGUOUS = '0OoIl1Ll5S2Zz8B';

    public function generate(PatternConfiguration $configuration, LicenseKeyGenerationContext $context): string
    {
        throw_if($configuration->pattern === '', InvalidArgumentException::class, 'Pattern is required for the pattern generator.');

        return preg_replace_callback('/\{([A-Za-z0-9]+)\}/', function (array $match) use ($configuration, $context): string {
            $token = $match[1];

            if ($token === 'uuid') {
                return (string) Str::uuid();
            }

            if ($token === 'year') {
                return (string) $context->year();
            }

            if ($token === 'month') {
                return mb_str_pad((string) $context->month(), 2, '0', STR_PAD_LEFT);
            }

            if ($token === 'product') {
                return $context->productSlug;
            }

            if ($token === 'customer') {
                return $context->customerCode;
            }

            return $this->expandPlaceholderRun($token, $configuration->excludeAmbiguousCharacters);
        }, $configuration->pattern) ?? $configuration->pattern;
    }

    private function expandPlaceholderRun(string $token, bool $excludeAmbiguous): string
    {
        $out = '';

        foreach (mb_str_split($token) as $char) {
            $out .= $this->randomFromClass($char, $excludeAmbiguous);
        }

        return $out;
    }

    private function randomFromClass(string $char, bool $excludeAmbiguous): string
    {
        $charset = match ($char) {
            'A' => self::UPPERCASE,
            'a' => self::LOWERCASE,
            '9' => self::NUMBERS,
            'X' => self::UPPERCASE.self::NUMBERS,
            'x' => self::UPPERCASE.self::LOWERCASE.self::NUMBERS,
            default => $char,
        };

        if (mb_strlen($charset) === 1) {
            return $charset;
        }

        if ($excludeAmbiguous) {
            $charset = str_replace(mb_str_split(self::AMBIGUOUS), '', $charset);
        }

        if ($charset === '') {
            return '';
        }

        return mb_substr($charset, random_int(0, mb_strlen($charset) - 1), 1);
    }
}
