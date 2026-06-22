<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\LicenseKeys\BulkCreateLicenseKeysAction;
use App\Enums\LicenseValidityUnit;
use App\Models\Customer;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LicenseKeysBulkCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;

#[Timeout(600)]
final class BulkCreateLicenseKeysJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $typeId,
        public readonly int $productId,
        public readonly ?int $customerId,
        public readonly int $count,
        public readonly string $validityUnit,
        public readonly int $validityAmount,
        public readonly ?int $maxActivations,
        public readonly bool $requiresHwidCheck,
        public readonly int $createdById,
    ) {}

    public function handle(BulkCreateLicenseKeysAction $bulk): void
    {
        $type = LicenseKeyType::query()->findOrFail($this->typeId);
        $product = Product::query()->findOrFail($this->productId);
        $customer = $this->customerId !== null
            ? Customer::query()->find($this->customerId)
            : null;
        $createdBy = User::query()->findOrFail($this->createdById);

        $created = $bulk->handle(
            $type,
            $product,
            $customer,
            $this->count,
            $this->validityAmount,
            LicenseValidityUnit::from($this->validityUnit),
            $this->maxActivations,
            $this->requiresHwidCheck,
            null,
            $createdBy,
        );

        /** @var LicenseKey[] $createdArr */
        $createdArr = $created->all();

        $createdBy->notify(new LicenseKeysBulkCreated(
            count: count($createdArr),
            product: $product,
        ));
    }
}
