<?php

declare(strict_types=1);

namespace App\Http\Resources\LicenseKeys;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
final class CustomerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'email' => $this->email,
            'name' => $this->name,
            'company' => $this->company,
            'metadata' => $this->metadata,
            'license_keys_count' => $this->whenCounted('licenseKeys'),
        ];
    }
}
