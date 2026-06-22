<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LicenseKeyGeneratorType;
use App\Models\LicenseKeyType;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LicenseKeyType>
 */
final class LicenseKeyTypeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => 'Standard License '.fake()->unique()->randomNumber(5),
            'description' => fake()->sentence(),
            'generator_type' => LicenseKeyGeneratorType::RANDOM->value,
            'configuration' => [
                'length' => 12,
                'group_length' => 4,
                'separator' => '-',
                'prefix' => 'LIC',
                'uppercase' => true,
                'lowercase' => false,
                'numbers' => true,
                'exclude_ambiguous_characters' => true,
                'case_sensitive' => false,
            ],
            'is_active' => true,
        ];
    }

    public function forTeam(Team $team): self
    {
        return $this->state(fn (): array => ['team_id' => $team->id]);
    }

    public function uuid(): self
    {
        return $this->state(fn (): array => [
            'generator_type' => LicenseKeyGeneratorType::UUID->value,
            'configuration' => [
                'uuid_version' => 'v4',
                'with_hyphens' => true,
                'uppercase' => false,
                'prefix' => '',
                'case_sensitive' => false,
            ],
        ]);
    }

    public function pattern(): self
    {
        return $this->state(fn (): array => [
            'generator_type' => LicenseKeyGeneratorType::PATTERN->value,
            'configuration' => [
                'pattern' => 'LIC-{XXXX}-{XXXX}-{XXXX}',
                'exclude_ambiguous_characters' => true,
                'case_sensitive' => false,
            ],
        ]);
    }
}
