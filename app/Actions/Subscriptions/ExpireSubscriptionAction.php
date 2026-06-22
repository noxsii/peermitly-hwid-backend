<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

final readonly class ExpireSubscriptionAction
{
    /**
     * Expire a single subscription if it is still active but its term has
     * elapsed. Returns true when the subscription was expired.
     */
    public function handle(Subscription $subscription): bool
    {
        if ($subscription->status !== SubscriptionStatus::ACTIVE) {
            return false;
        }

        if (! $subscription->ends_at->isPast()) {
            return false;
        }

        $subscription->update(['status' => SubscriptionStatus::EXPIRED]);

        return true;
    }
}
