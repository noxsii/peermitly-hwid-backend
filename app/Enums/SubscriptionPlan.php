<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Carbon;

enum SubscriptionPlan: string
{
    case FREE = 'free';
    case TRIAL = 'trial';
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
    case LIFETIME = 'lifetime';

    public function label(): string
    {
        return match ($this) {
            self::FREE => 'Free',
            self::TRIAL => 'Trial',
            self::DAY => 'Day pass',
            self::WEEK => 'Weekly',
            self::MONTH => 'Monthly',
            self::LIFETIME => 'Lifetime',
        };
    }

    public function days(): int
    {
        return match ($this) {
            self::TRIAL => 14,
            self::DAY => 1,
            self::WEEK => 7,
            self::MONTH => 30,
            // Free and Lifetime never really expire; we anchor them ~100 years
            // out so the "active" check (ends_at in the future) always holds.
            self::FREE, self::LIFETIME => 36_525,
        };
    }

    public function isFree(): bool
    {
        return $this === self::FREE;
    }

    public function isPro(): bool
    {
        return $this !== self::FREE;
    }

    public function isLifetime(): bool
    {
        return $this === self::LIFETIME;
    }

    /**
     * Plans that never expire (Free and Lifetime).
     */
    public function isPerpetual(): bool
    {
        return $this === self::FREE || $this === self::LIFETIME;
    }

    public function isTrial(): bool
    {
        return $this === self::TRIAL;
    }

    /**
     * Compute the expiry timestamp for a subscription of this plan starting at $start.
     */
    public function expiresFrom(Carbon $start): Carbon
    {
        return $start->copy()->addDays($this->days());
    }
}
