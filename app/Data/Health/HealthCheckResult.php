<?php

declare(strict_types=1);

namespace App\Data\Health;

use App\Enums\HealthStatus;

final readonly class HealthCheckResult
{
    public function __construct(
        public string $name,
        public HealthStatus $status,
        public int $latencyMs,
        public ?string $message = null,
    ) {}

    /**
     * @return array{name: string, status: string, latency_ms: int, message: string|null}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'status' => $this->status->value,
            'latency_ms' => $this->latencyMs,
            'message' => $this->message,
        ];
    }
}
