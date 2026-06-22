<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

use App\Enums\LicenseKeyStatus;

final readonly class LicenseKeyExportFilters
{
    public function __construct(
        public int $teamId,
        public ?LicenseKeyStatus $status,
        public ?int $productId,
        public string $delimiter,
    ) {}

    public static function fromRequest(int $teamId, ?LicenseKeyStatus $status, ?int $productId, string $delimiterInput): self
    {
        return new self(
            teamId: $teamId,
            status: $status,
            productId: $productId,
            delimiter: match ($delimiterInput) {
                ';' => ';',
                "\t", 'tab' => "\t",
                default => ',',
            },
        );
    }
}
