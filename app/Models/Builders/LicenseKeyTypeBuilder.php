<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Models\LicenseKeyType;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModel of LicenseKeyType
 *
 * @extends Builder<TModel>
 */
final class LicenseKeyTypeBuilder extends Builder
{
    /**
     * @return self<TModel>
     */
    public function whereTeam(Team|int $team): self
    {
        return $this->where('team_id', $team instanceof Team ? $team->id : $team);
    }

    /**
     * @return self<TModel>
     */
    public function whereActive(): self
    {
        return $this->where('is_active', true);
    }
}
