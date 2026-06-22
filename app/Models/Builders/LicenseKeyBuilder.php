<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Enums\LicenseKeyStatus;
use App\Models\LicenseKey;
use App\Models\Product;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModel of LicenseKey
 *
 * @extends Builder<TModel>
 */
final class LicenseKeyBuilder extends Builder
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
    public function wherePending(): self
    {
        return $this->where('status', LicenseKeyStatus::PENDING->value);
    }

    /**
     * @return self<TModel>
     */
    public function whereActive(): self
    {
        return $this->where('status', LicenseKeyStatus::ACTIVE->value);
    }

    /**
     * @return self<TModel>
     */
    public function whereExpired(): self
    {
        return $this->where('status', LicenseKeyStatus::EXPIRED->value);
    }

    /**
     * @return self<TModel>
     */
    public function whereRevoked(): self
    {
        return $this->where('status', LicenseKeyStatus::REVOKED->value);
    }

    /**
     * @return self<TModel>
     */
    public function whereNormalizedKey(string $normalized): self
    {
        return $this->where('normalized_key', $normalized);
    }

    /**
     * @return self<TModel>
     */
    public function whereForProduct(Product|int $product): self
    {
        return $this->where('product_id', $product instanceof Product ? $product->id : $product);
    }
}
