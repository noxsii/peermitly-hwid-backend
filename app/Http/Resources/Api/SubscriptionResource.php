<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Subscription
 */
final class SubscriptionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'plan' => $this->plan->value,
            'plan_label' => $this->plan->label(),
            'status' => $this->status->value,
            'is_trial' => $this->plan->isTrial(),
            'is_lifetime' => $this->plan->isLifetime(),
            'is_free' => $this->plan->isFree(),
            'is_pro' => $this->plan->isPro(),
            'starts_at' => $this->starts_at->toIso8601String(),
            'ends_at' => $this->ends_at->toIso8601String(),
            'days_remaining' => $this->plan->isPerpetual()
                ? null
                : max(0, (int) round(today()->diffInDays($this->ends_at->copy()->startOfDay()))),
        ];
    }
}
