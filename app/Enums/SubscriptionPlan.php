<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Carbon;

enum SubscriptionPlan: string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';

    public function label(): string
    {
        return match ($this) {
            self::DAY => 'Day pass',
            self::WEEK => 'Weekly',
            self::MONTH => 'Monthly',
        };
    }

    public function days(): int
    {
        return match ($this) {
            self::DAY => 1,
            self::WEEK => 7,
            self::MONTH => 30,
        };
    }

    /**
     * Compute the expiry timestamp for a subscription of this plan starting at $start.
     */
    public function expiresFrom(Carbon $start): Carbon
    {
        return $start->copy()->addDays($this->days());
    }
}
