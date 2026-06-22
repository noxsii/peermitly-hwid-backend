<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Builders\SubscriptionBuilder;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property SubscriptionPlan $plan
 * @property SubscriptionStatus $status
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property Carbon|null $canceled_at
 * @property string|null $stripe_subscription_id
 * @property string|null $stripe_customer_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static SubscriptionFactory factory($count = null, $state = [])
 * @method static SubscriptionBuilder<static>|Subscription newModelQuery()
 * @method static SubscriptionBuilder<static>|Subscription newQuery()
 * @method static SubscriptionBuilder<static>|Subscription query()
 * @method static SubscriptionBuilder<static>|Subscription whereActive()
 * @method static SubscriptionBuilder<static>|Subscription whereCanceledAt($value)
 * @method static SubscriptionBuilder<static>|Subscription whereCreatedAt($value)
 * @method static SubscriptionBuilder<static>|Subscription whereEndsAt($value)
 * @method static SubscriptionBuilder<static>|Subscription whereId($value)
 * @method static SubscriptionBuilder<static>|Subscription wherePlan($value)
 * @method static SubscriptionBuilder<static>|Subscription whereStartsAt($value)
 * @method static SubscriptionBuilder<static>|Subscription whereStatus($value)
 * @method static SubscriptionBuilder<static>|Subscription whereStripeCustomerId($value)
 * @method static SubscriptionBuilder<static>|Subscription whereStripeSubscriptionId($value)
 * @method static SubscriptionBuilder<static>|Subscription whereUpdatedAt($value)
 * @method static SubscriptionBuilder<static>|Subscription whereUserId($value)
 * @method static SubscriptionBuilder<static>|Subscription whereUuid($value)
 *
 * @mixin Model
 */
#[UseEloquentBuilder(SubscriptionBuilder::class)]
#[Fillable(
    'user_id',
    'plan',
    'status',
    'starts_at',
    'ends_at',
    'canceled_at',
    'stripe_subscription_id',
    'stripe_customer_id',
)]
final class Subscription extends Model
{
    /** @use HasFactory<SubscriptionFactory> */
    use HasFactory;

    use HasUuids;

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'user_id' => 'integer',
            'plan' => SubscriptionPlan::class,
            'status' => SubscriptionStatus::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'canceled_at' => 'datetime',
            'stripe_subscription_id' => 'string',
            'stripe_customer_id' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
