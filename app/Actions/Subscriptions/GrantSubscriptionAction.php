<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Carbon;

final readonly class GrantSubscriptionAction
{
    /**
     * Grant a user access for the given plan. The expiry is derived from the
     * plan duration unless an explicit $endsAt is provided (manual override).
     */
    public function handle(
        User $user,
        SubscriptionPlan $plan,
        ?Carbon $startsAt = null,
        ?Carbon $endsAt = null,
    ): Subscription {
        $start = $startsAt ?? now();

        return $user->subscriptions()->create([
            'plan' => $plan,
            'status' => SubscriptionStatus::ACTIVE,
            'starts_at' => $start,
            'ends_at' => $endsAt ?? $plan->expiresFrom($start),
        ]);
    }
}
