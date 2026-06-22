<?php

declare(strict_types=1);

namespace App\Http\Resources\LicenseKeys;

use App\Models\LicenseKey;
use App\Models\LicenseKeyActivation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin LicenseKey
 */
final class LicenseKeyResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'key' => $this->key,
            'status' => $this->status->value,
            'product' => [
                'uuid' => $this->product->uuid,
                'name' => $this->product->name,
                'slug' => $this->product->slug,
            ],
            'customer' => $this->customer === null ? null : [
                'uuid' => $this->customer->uuid,
                'email' => $this->customer->email,
                'name' => $this->customer->name,
            ],
            'type' => [
                'uuid' => $this->type->uuid,
                'name' => $this->type->name,
            ],
            'validity_amount' => $this->validity_amount,
            'validity_unit' => $this->validity_unit->value,
            'max_activations' => $this->max_activations,
            'requires_hwid_check' => $this->requires_hwid_check,
            'activated_at' => $this->activated_at?->toIso8601String(),
            'expires_at' => $this->expires_at?->toIso8601String(),
            'last_checked_at' => $this->last_checked_at?->toIso8601String(),
            'check_count' => $this->check_count,
            'revoked_at' => $this->revoked_at?->toIso8601String(),
            'revoked_reason' => $this->revoked_reason,
            'created_at' => $this->created_at?->toIso8601String(),
            'activations' => $this->whenLoaded('activations', fn () => $this->activations->map(static fn (LicenseKeyActivation $a): array => [
                'uuid' => $a->uuid,
                'machine_id' => $a->machine_id,
                'hostname' => $a->hostname,
                'ip_address' => $a->ip_address,
                'activated_at' => $a->activated_at->toIso8601String(),
                'last_seen_at' => $a->last_seen_at?->toIso8601String(),
            ])->values()),
        ];
    }
}
