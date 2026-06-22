<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

use App\Models\Product;
use Illuminate\Http\Request;

final readonly class LicenseKeyCheckRequest
{
    public function __construct(
        public string $rawKey,
        public Product $product,
        public ?string $hwid,
        public int $teamId,
        public ?string $ipAddress,
        public ?string $userAgent,
    ) {}

    public static function fromHttpRequest(string $rawKey, Product $product, ?string $hwid, int $teamId, Request $request): self
    {
        return new self(
            rawKey: $rawKey,
            product: $product,
            hwid: $hwid !== null && $hwid !== '' ? $hwid : null,
            teamId: $teamId,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
        );
    }
}
