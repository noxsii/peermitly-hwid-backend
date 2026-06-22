<?php

declare(strict_types=1);

namespace App\Http\Resources\LicenseKeys;

use App\Models\LicenseKeyType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin LicenseKeyType
 */
final class LicenseKeyTypeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'generator_type' => $this->generator_type->value,
            'configuration' => $this->configuration,
            'is_active' => $this->is_active,
            'license_keys_count' => $this->whenCounted('licenseKeys'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
