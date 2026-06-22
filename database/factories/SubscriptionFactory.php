<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
final class SubscriptionFactory extends Factory
{
    /**
     * @var class-string<Subscription>
     */
    protected $model = Subscription::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plan = fake()->randomElement(SubscriptionPlan::cases());
        // Keep the default subscription reliably active: the start is recent
        // enough that even a one-day plan still expires in the future.
        $start = now()->subHours(fake()->numberBetween(0, 12));

        return [
            'user_id' => User::factory(),
            'plan' => $plan,
            'status' => SubscriptionStatus::ACTIVE,
            'starts_at' => $start,
            'ends_at' => $plan->expiresFrom($start),
            'canceled_at' => null,
            'stripe_subscription_id' => null,
            'stripe_customer_id' => null,
        ];
    }

    public function plan(SubscriptionPlan $plan): self
    {
        return $this->state(function (array $attributes) use ($plan): array {
            $start = $attributes['starts_at'] ?? now();

            return [
                'plan' => $plan,
                'ends_at' => $plan->expiresFrom($start),
            ];
        });
    }

    public function expired(): self
    {
        return $this->state(fn (): array => [
            'status' => SubscriptionStatus::EXPIRED,
            'starts_at' => now()->subDays(40),
            'ends_at' => now()->subDay(),
        ]);
    }

    public function canceled(): self
    {
        return $this->state(fn (): array => [
            'status' => SubscriptionStatus::CANCELED,
            'canceled_at' => now(),
        ]);
    }
}
