<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Health;

use App\Contracts\SystemHealthChecker;
use App\Enums\HealthStatus;
use Illuminate\Http\JsonResponse;

final class HealthCheckController
{
    public function status(SystemHealthChecker $checker): JsonResponse
    {
        $health = $checker->handle();

        $httpStatus = match ($health->status) {
            HealthStatus::OK, HealthStatus::DEGRADED => 200,
            HealthStatus::DOWN => 503,
        };

        return new JsonResponse($health->toArray(), $httpStatus);
    }
}
