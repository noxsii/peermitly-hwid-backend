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
     * @var class-string<AppRelease>
     */
    protected $model = AppRelease::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $version = fake()->unique()->numerify('#.#.#');

        return [
            'version' => $version,
            'platform' => 'windows-x86_64',
            'notes' => fake()->optional()->sentence(),
            'signature' => 'sig:'.fake()->sha256(),
            'file_path' => 'app-releases/peermitly-'.$version.'.exe',
            'file_name' => 'peermitly-'.$version.'.exe',
            'file_size' => fake()->numberBetween(5_000_000, 80_000_000),
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'is_active' => true,
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }

    public function version(string $version): self
    {
        return $this->state(fn (): array => [
            'version' => $version,
            'file_path' => 'app-releases/peermitly-'.$version.'.exe',
            'file_name' => 'peermitly-'.$version.'.exe',
        ]);
    }
}
