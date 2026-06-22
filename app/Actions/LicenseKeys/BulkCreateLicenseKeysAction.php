<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Enums\LicenseValidityUnit;
use App\Models\Customer;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final readonly class BulkCreateLicenseKeysAction
{
    public function __construct(
        private CreateLicenseKeyAction $create,
    ) {}

    /**
     * @param  array<string, mixed>|null  $metadata
     * @return Collection<int, LicenseKey>
     */
    public function handle(
        LicenseKeyType $type,
        Product $product,
        ?Customer $customer,
        int $count,
        int $validityAmount,
        LicenseValidityUnit $validityUnit,
        ?int $maxActivations,
        bool $requiresHwidCheck,
        ?array $metadata,
        User $createdBy,
    ): Collection {
        /** @var Collection<int, LicenseKey> $created */
        $created = new Collection();

        DB::transaction(function () use (
            $type,
            $product,
            $customer,
            $count,
            $validityAmount,
            $validityUnit,
            $maxActivations,
            $requiresHwidCheck,
            $metadata,
            $createdBy,
            $created,
        ): void {
            for ($i = 0; $i < $count; $i++) {
                $created->push($this->create->handle(
                    $type,
                    $product,
                    $customer,
                    $validityAmount,
                    $validityUnit,
                    $maxActivations,
                    $requiresHwidCheck,
                    $metadata,
                    $createdBy,
                ));
            }
        });

        return $created;
    }
}
