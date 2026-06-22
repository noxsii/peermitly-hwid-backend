<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LicenseCheckStatus;
use App\Models\LicenseKey;
use App\Models\LicenseKeyCheck;
use App\Models\Product;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LicenseKeyCheck>
 */
final class LicenseKeyCheckFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'license_key_id' => LicenseKey::factory(),
            'product_id' => Product::factory(),
            'status' => LicenseCheckStatus::VALID->value,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'hwid' => null,
            'request_payload' => [],
            'response_payload' => [],
            'checked_at' => now(),
        ];
    }
}
