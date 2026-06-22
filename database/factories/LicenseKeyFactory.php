<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Actions\LicenseKeys\NormalizeLicenseKeyAction;
use App\Enums\LicenseKeyStatus;
use App\Enums\LicenseValidityUnit;
use App\Models\Customer;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @extends Factory<LicenseKey>
 */
final class LicenseKeyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $key = 'LIC-'.mb_strtoupper(Str::random(4)).'-'.mb_strtoupper(Str::random(4)).'-'.mb_strtoupper(Str::random(4));
        $normalized = new NormalizeLicenseKeyAction()->handle($key, false);

        return [
            'team_id' => Team::factory(),
            'license_key_type_id' => LicenseKeyType::factory()->state(fn (array $attributes, ?Model $parent): array => ['team_id' => $parent instanceof LicenseKey ? $parent->team_id : Team::factory()]),
            'product_id' => Product::factory(),
            'customer_id' => null,
            'created_by' => User::factory(),
            'key' => $key,
            'normalized_key' => $normalized,
            'status' => LicenseKeyStatus::PENDING->value,
            'validity_amount' => 12,
            'validity_unit' => LicenseValidityUnit::MONTHS->value,
            'max_activations' => null,
            'requires_hwid_check' => false,
            'activated_at' => null,
            'expires_at' => null,
            'last_checked_at' => null,
            'check_count' => 0,
            'revoked_at' => null,
            'revoked_reason' => null,
            'metadata' => null,
        ];
    }

    public function forTeam(Team $team): self
    {
        return $this->state(fn (): array => ['team_id' => $team->id]);
    }

    public function forType(LicenseKeyType $type): self
    {
        return $this->state(fn (): array => [
            'team_id' => $type->team_id,
            'license_key_type_id' => $type->id,
        ]);
    }

    public function forProduct(Product $product): self
    {
        return $this->state(fn (): array => [
            'team_id' => $product->team_id,
            'product_id' => $product->id,
        ]);
    }

    public function forCustomer(Customer $customer): self
    {
        return $this->state(fn (): array => [
            'team_id' => $customer->team_id,
            'customer_id' => $customer->id,
        ]);
    }

    public function active(): self
    {
        return $this->state(fn (): array => [
            'status' => LicenseKeyStatus::ACTIVE->value,
            'activated_at' => now()->subDay(),
            'expires_at' => now()->addMonths(12),
        ]);
    }

    public function expired(): self
    {
        return $this->state(fn (): array => [
            'status' => LicenseKeyStatus::EXPIRED->value,
            'activated_at' => now()->subYears(2),
            'expires_at' => now()->subMonth(),
        ]);
    }

    public function revoked(string $reason = 'Test revoke'): self
    {
        return $this->state(fn (): array => [
            'status' => LicenseKeyStatus::REVOKED->value,
            'activated_at' => now()->subMonth(),
            'expires_at' => now()->addMonths(11),
            'revoked_at' => now()->subDay(),
            'revoked_reason' => $reason,
        ]);
    }

    public function lifetime(): self
    {
        return $this->state(fn (): array => [
            'status' => LicenseKeyStatus::ACTIVE->value,
            'validity_amount' => null,
            'validity_unit' => LicenseValidityUnit::LIFETIME->value,
            'activated_at' => now()->subDay(),
            'expires_at' => null,
        ]);
    }

    public function requiresHwid(): self
    {
        return $this->state(fn (): array => [
            'requires_hwid_check' => true,
        ]);
    }
}
