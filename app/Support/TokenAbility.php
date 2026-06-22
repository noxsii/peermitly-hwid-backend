<?php

declare(strict_types=1);

namespace App\Support;

final class TokenAbility
{
    public const string LICENSE_KEYS_CHECK = 'license-keys:check';

    public const string LICENSE_KEYS_READ = 'license-keys:read';

    public const string LICENSE_KEYS_MANAGE = 'license-keys:manage';

    public const string LICENSE_KEY_TYPES_MANAGE = 'license-key-types:manage';

    /**
     * @return array<int, string>
     */
    public static function all(): array
    {
        return [
            self::LICENSE_KEYS_CHECK,
            self::LICENSE_KEYS_READ,
            self::LICENSE_KEYS_MANAGE,
            self::LICENSE_KEY_TYPES_MANAGE,
        ];
    }
}
