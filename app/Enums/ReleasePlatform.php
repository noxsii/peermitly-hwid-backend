<?php

declare(strict_types=1);

namespace App\Enums;

enum ReleasePlatform: string
{
    case DarwinAarch64 = 'darwin-aarch64';
    case DarwinX86_64 = 'darwin-x86_64';
    case WindowsX86_64 = 'windows-x86_64';
    case LinuxX86_64 = 'linux-x86_64';

    public function label(): string
    {
        return match ($this) {
            self::DarwinAarch64 => 'macOS (Apple Silicon)',
            self::DarwinX86_64 => 'macOS (Intel)',
            self::WindowsX86_64 => 'Windows (x64)',
            self::LinuxX86_64 => 'Linux (x64)',
        };
    }
}
