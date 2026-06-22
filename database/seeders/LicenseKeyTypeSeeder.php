<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\LicenseKeyGeneratorType;
use App\Models\LicenseKeyType;
use App\Models\Team;
use Illuminate\Database\Seeder;

final class LicenseKeyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $teams = Team::query()->get();

        foreach ($teams as $team) {
            foreach ($this->defaults() as $definition) {
                LicenseKeyType::query()->updateOrCreate(
                    [
                        'team_id' => $team->id,
                        'name' => $definition['name'],
                    ],
                    [
                        'description' => $definition['description'],
                        'generator_type' => $definition['generator_type'],
                        'configuration' => $definition['configuration'],
                        'is_active' => true,
                    ],
                );
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaults(): array
    {
        return [
            [
                'name' => 'Standard License',
                'description' => 'Twelve-character grouped key. Best default for normal licenses.',
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
            ],
            [
                'name' => 'Trial License',
                'description' => 'Shorter key for trial and demo distributions.',
                'generator_type' => LicenseKeyGeneratorType::RANDOM->value,
                'configuration' => [
                    'length' => 8,
                    'group_length' => 4,
                    'separator' => '-',
                    'prefix' => 'TRIAL',
                    'uppercase' => true,
                    'lowercase' => false,
                    'numbers' => true,
                    'exclude_ambiguous_characters' => true,
                    'case_sensitive' => false,
                ],
            ],
            [
                'name' => 'Enterprise License',
                'description' => 'Longer high-entropy key for enterprise contracts.',
                'generator_type' => LicenseKeyGeneratorType::RANDOM->value,
                'configuration' => [
                    'length' => 16,
                    'group_length' => 4,
                    'separator' => '-',
                    'prefix' => 'ENT',
                    'uppercase' => true,
                    'lowercase' => false,
                    'numbers' => true,
                    'exclude_ambiguous_characters' => true,
                    'case_sensitive' => false,
                ],
            ],
            [
                'name' => 'UUID License',
                'description' => 'UUID-based key for machine-readable scenarios.',
                'generator_type' => LicenseKeyGeneratorType::UUID->value,
                'configuration' => [
                    'uuid_version' => 'v4',
                    'with_hyphens' => true,
                    'uppercase' => false,
                    'prefix' => '',
                    'case_sensitive' => true,
                ],
            ],
        ];
    }
}
