<?php

declare(strict_types=1);

namespace App\Data\Api;

use App\Models\User;

final readonly class ApiAuthResult
{
    public function __construct(
        public User $user,
        public string $token,
    ) {}
}
