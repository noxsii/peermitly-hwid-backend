<?php

declare(strict_types=1);

namespace App\Data\Health;

use App\Enums\HealthStatus;
use Illuminate\Support\Carbon;

final readonly class SystemHealthData
{
    /**
     * @param  array<int, HealthCheckResult>  $checks
     */
    public function __construct(
        public HealthStatus $status,
        public Carbon $checkedAt,
        public array $checks,
    ) {}

    /**
     * @return array{status: string, checked_at: string, checks: array<int, array{name: string, status: string, latency_ms: int, message: string|null}>}
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status->value,
            'checked_at' => $this->checkedAt->toIso8601String(),
            'checks' => array_map(
                static fn (HealthCheckResult $check): array => $check->toArray(),
                $this->checks,
            ),
        ];
    }
}
