<?php

declare(strict_types=1);

namespace App\Actions\Logging;

use App\Data\Logging\ApiRequestLogData;
use App\Models\ApiRequestLog;

final class StoreApiRequestLogAction
{
    public function handle(ApiRequestLogData $data): ApiRequestLog
    {
        return ApiRequestLog::query()->create([
            'method' => $data->method,
            'path' => $data->path,
            'route_name' => $data->routeName,
            'status' => $data->status,
            'user_id' => $data->userId,
            'license_key_id' => $data->licenseKeyId,
            'ip' => $data->ip,
            'user_agent' => $data->userAgent,
            'duration_ms' => $data->durationMs,
            'request_payload' => $data->requestPayload,
            'created_at' => $data->createdAt,
        ]);
    }
}
