<?php

declare(strict_types=1);

namespace App\Services\LicenseKeys\Generators;

use App\Data\LicenseKeys\RandomCharacterConfiguration;
use InvalidArgumentException;

final class RandomCharacterLicenseKeyGenerator
{
    private const string UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private const string LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';

    private const string NUMBERS = '0123456789';

    private const string SPECIAL = '!@#$%&*?_-';

    private const string AMBIGUOUS = '0OoIl1Ll5S2Zz8B';

    public function generate(RandomCharacterConfiguration $configuration): string
    {
        $charset = $this->buildCharset($configuration);

        throw_if($charset === '', InvalidArgumentException::class, 'License key charset is empty. Configure at least one character class.');

        $body = '';
        $charsetLength = mb_strlen($charset);

        for ($i = 0; $i < $configuration->length; $i++) {
            $body .= mb_substr($charset, random_int(0, $charsetLength - 1), 1);
        }

        if ($configuration->groupLength > 0 && $configuration->groupLength < $configuration->length && $configuration->separator !== '') {
            $body = mb_trim(chunk_split($body, $configuration->groupLength, $configuration->separator), $configuration->separator);
        }

        if ($configuration->prefix !== '') {
            $body = $configuration->prefix.$configuration->prefixSeparator.$body;
        }

        if ($configuration->suffix !== '') {
            $body .= $configuration->suffixSeparator.$configuration->suffix;
        }

        return $body;
    }

    private function buildCharset(RandomCharacterConfiguration $configuration): string
    {
        if ($configuration->customCharset !== null && $configuration->customCharset !== '') {
            $charset = $configuration->customCharset;
        } else {
            $charset = '';

            if ($configuration->uppercase) {
                $charset .= self::UPPERCASE;
            }

            if ($configuration->lowercase) {
                $charset .= self::LOWERCASE;
            }

            if ($configuration->numbers) {
                $charset .= self::NUMBERS;
            }

            if ($configuration->specialCharacters) {
                $charset .= self::SPECIAL;
            }
        }

        if ($configuration->excludeAmbiguousCharacters) {
            return str_replace(mb_str_split(self::AMBIGUOUS), '', $charset);
        }

        return $charset;
    }
}
