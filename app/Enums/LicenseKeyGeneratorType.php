<?php

declare(strict_types=1);

namespace App\Enums;

enum LicenseKeyGeneratorType: string
{
    case UUID = 'uuid';
    case RANDOM = 'random';
    case PATTERN = 'pattern';

    public function label(): string
    {
        return match ($this) {
            self::UUID => 'UUID',
            self::RANDOM => 'Random Characters',
            self::PATTERN => 'Pattern Based',
        };
    }
}
