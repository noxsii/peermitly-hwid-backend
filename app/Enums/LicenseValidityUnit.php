<?php

declare(strict_types=1);

namespace App\Enums;

use Carbon\CarbonInterface;

enum LicenseValidityUnit: string
{
    case HOURS = 'hours';
    case DAYS = 'days';
    case WEEKS = 'weeks';
    case MONTHS = 'months';
    case YEARS = 'years';
    case LIFETIME = 'lifetime';

    public function label(): string
    {
        return match ($this) {
            self::HOURS => 'Hours',
            self::DAYS => 'Days',
            self::WEEKS => 'Weeks',
            self::MONTHS => 'Months',
            self::YEARS => 'Years',
            self::LIFETIME => 'Lifetime',
        };
    }

    public function applyTo(CarbonInterface $from, int $amount): CarbonInterface
    {
        return match ($this) {
            self::HOURS => $from->copy()->addHours($amount),
            self::DAYS => $from->copy()->addDays($amount),
            self::WEEKS => $from->copy()->addWeeks($amount),
            self::MONTHS => $from->copy()->addMonths($amount),
            self::YEARS => $from->copy()->addYears($amount),
            self::LIFETIME => $from->copy(),
        };
    }

    public function isLifetime(): bool
    {
        return $this === self::LIFETIME;
    }
}
