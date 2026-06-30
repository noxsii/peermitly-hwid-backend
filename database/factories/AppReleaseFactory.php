<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AppRelease;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AppRelease>
 */
final class AppReleaseFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $version = $this->faker->numerify('#.#.#');

        return [
            'version' => $version,
            'notes' => $this->faker->sentence(),
            'platforms' => [
                [
                    'platform' => 'darwin-aarch64',
                    'path' => "releases/peermitly_{$version}_universal.app.tar.gz",
                    'signature' => $this->faker->sha256(),
                ],
                [
                    'platform' => 'windows-x86_64',
                    'path' => "releases/peermitly_{$version}_x64-setup.exe",
                    'signature' => $this->faker->sha256(),
                ],
            ],
            'is_published' => true,
            'published_at' => now(),
        ];
    }

    public function unpublished(): self
    {
        return $this->state(fn (): array => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
