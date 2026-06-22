<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LicenseKey;
use App\Models\LicenseKeyActivation;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LicenseKeyActivation>
 */
final class LicenseKeyActivationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'license_key_id' => LicenseKey::factory(),
            'machine_id' => 'machine-'.Str::random(12),
            'hostname' => fake()->domainName(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'activated_at' => now()->subHour(),
            'last_seen_at' => now(),
            'revoked_at' => null,
            'metadata' => null,
        ];
    }

    public function forLicenseKey(LicenseKey $licenseKey): self
    {
        return $this->state(fn (): array => [
            'team_id' => $licenseKey->team_id,
            'license_key_id' => $licenseKey->id,
        ]);
    }
}
