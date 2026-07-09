<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModel of Subscription
 *
 * @extends Builder<TModel>
 */
final class SubscriptionBuilder extends Builder
{
    /**
     * Limit the query to subscriptions that currently grant access:
     * active status and not yet expired.
     */
    public function whereActive(): static
    {
        return $this->where('status', SubscriptionStatus::ACTIVE->value)
            ->where('ends_at', '>=', now());
    }

    /**
     * Limit the query to paid (non-free) subscriptions.
     */
    public function wherePro(): static
    {
        return $this->where('plan', '!=', SubscriptionPlan::FREE->value);
    }

    /**
     * Limit the query to subscriptions still flagged active whose term has
     * already elapsed — i.e. those that should be expired.
     */
    public function wherePastDue(): static
    {
        return $this->where('status', SubscriptionStatus::ACTIVE->value)
            ->where('ends_at', '<', now());
    }
}
