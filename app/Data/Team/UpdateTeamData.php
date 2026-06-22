<?php

declare(strict_types=1);

namespace App\Data\Team;

final readonly class UpdateTeamData
{
    public function __construct(
        public string $name,
    ) {}
}
