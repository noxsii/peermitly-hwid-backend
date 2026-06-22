<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ApiRequestLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ApiRequestLog
 */
final class ApiRequestLogResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'method' => $this->method,
            'path' => $this->path,
            'status' => $this->status,
            'duration_ms' => $this->duration_ms,
            'ip' => $this->ip,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
