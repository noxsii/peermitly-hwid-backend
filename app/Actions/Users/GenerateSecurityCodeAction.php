<?php

declare(strict_types=1);

namespace App\Actions\Users;

use Random\RandomException;

final readonly class GenerateSecurityCodeAction
{
    /**
     * Characters used for security codes, excluding visually ambiguous
     * symbols (0/O, 1/I/L) so codes are easy to read aloud for support.
     */
    private const string ALPHABET = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';

    private const int LENGTH = 4;

    /**
     * Generate a four-character security code used to verify a user's
     * identity when they open a support ticket.
     *
     * @throws RandomException
     */
    public function handle(): string
    {
        $max = mb_strlen(self::ALPHABET) - 1;
        $code = '';

        for ($i = 0; $i < self::LENGTH; $i++) {
            $code .= self::ALPHABET[random_int(0, $max)];
        }

        return $code;
    }
}
