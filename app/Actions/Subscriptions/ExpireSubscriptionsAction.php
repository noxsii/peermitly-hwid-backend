<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

final readonly class ExpireSubscriptionsAction
{
    /**
     * Flag every active subscription whose term has elapsed as expired.
     *
     * @return int The number of subscriptions that were expired.
     */
    public function handle(): int
    {
        return Subscription::query()
            ->wherePastDue()
            ->update(['status' => SubscriptionStatus::EXPIRED->value]);
    }
}
