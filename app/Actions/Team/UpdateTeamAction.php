<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Data\Team\UpdateTeamData;
use App\Models\Team;

final class UpdateTeamAction
{
    public function handle(Team $team, UpdateTeamData $data): void
    {
        $team->update(['name' => $data->name]);
    }
}
