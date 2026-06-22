<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Data\LicenseKeys\LicenseKeyGenerationContext;
use App\Enums\LicenseKeyStatus;
use App\Enums\LicenseValidityUnit;
use App\Models\Customer;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\User;

final readonly class CreateLicenseKeyAction
{
    public function __construct(
        private GenerateLicenseKeyAction $generate,
    ) {}

    /**
     * @param  array<string, mixed>|null  $metadata  Free-form user metadata.
     */
    public function handle(
        LicenseKeyType $type,
        Product $product,
        ?Customer $customer,
        int $validityAmount,
        LicenseValidityUnit $validityUnit,
        ?int $maxActivations,
        bool $requiresHwidCheck,
        ?array $metadata,
        User $createdBy,
    ): LicenseKey {
        $context = new LicenseKeyGenerationContext(
            productSlug: $product->slug,
            customerCode: $customer instanceof Customer ? $customer->email : '',
        );

        $generated = $this->generate->handle($type, $context);

        return LicenseKey::query()->create([
            'team_id' => $type->team_id,
            'license_key_type_id' => $type->id,
            'product_id' => $product->id,
            'customer_id' => $customer?->id,
            'created_by' => $createdBy->id,
            'key' => $generated->key,
            'normalized_key' => $generated->normalizedKey,
            'status' => LicenseKeyStatus::PENDING->value,
            'validity_amount' => $validityUnit->isLifetime() ? null : $validityAmount,
            'validity_unit' => $validityUnit->value,
            'max_activations' => $maxActivations,
            'requires_hwid_check' => $requiresHwidCheck,
            'metadata' => $metadata,
        ]);
    }
}
