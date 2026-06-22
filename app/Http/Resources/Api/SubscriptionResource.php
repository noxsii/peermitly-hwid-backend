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
            'starts_at' => $this->starts_at->toIso8601String(),
            'ends_at' => $this->ends_at->toIso8601String(),
            'days_remaining' => max(0, (int) ceil(now()->diffInDays($this->ends_at))),
        ];
    }
}
