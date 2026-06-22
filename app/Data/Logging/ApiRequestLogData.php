<?php

declare(strict_types=1);

namespace App\Data\Logging;

use Illuminate\Support\Carbon;

final readonly class ApiRequestLogData
{
    /**
     * @param  array<string, mixed>|null  $requestPayload
     */
    public function __construct(
        public string $method,
        public string $path,
        public ?string $routeName,
        public int $status,
        public ?int $userId,
        public ?int $licenseKeyId,
        public ?string $ip,
        public ?string $userAgent,
        public ?int $durationMs,
        public ?array $requestPayload,
        public Carbon $createdAt,
    ) {}
}
