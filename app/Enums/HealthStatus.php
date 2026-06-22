<?php

declare(strict_types=1);

namespace App\Enums;

enum HealthStatus: string
{
    case OK = 'ok';
    case DEGRADED = 'degraded';
    case DOWN = 'down';
}
