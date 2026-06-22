<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Data\Health\SystemHealthData;

interface SystemHealthChecker
{
    public function handle(): SystemHealthData;
}
