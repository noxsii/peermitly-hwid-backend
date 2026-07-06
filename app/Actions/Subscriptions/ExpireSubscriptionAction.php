<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

final readonly class ExpireSubscriptionAction
{
    public function __construct(private GrantSubscriptionAction $grantSubscription) {}

    /**
     * Expire a single subscription if it is still active but its term has
     * elapsed, then fall the user back to the Free plan unless another
     * subscription still grants access. Returns true when the subscription
     * was expired.
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

        $user = $subscription->user;

        if (Subscription::query()->whereActive()->whereBelongsTo($user)->doesntExist()) {
            $this->grantSubscription->handle($user, SubscriptionPlan::FREE);
        }

        return true;
    }
}
