<?php

declare(strict_types=1);

namespace App\Models\Builders;

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
}
