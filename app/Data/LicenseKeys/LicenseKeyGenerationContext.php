<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

use Illuminate\Support\Facades\Date;

final readonly class LicenseKeyGenerationContext
{
    public function __construct(
        public string $productSlug = '',
        public string $customerCode = '',
        public ?int $year = null,
        public ?int $month = null,
    ) {}

    public static function empty(): self
    {
        return new self();
    }

    public function year(): int
    {
        return $this->year ?? Date::now()->year;
    }

    public function month(): int
    {
        return $this->month ?? Date::now()->month;
    }
}
