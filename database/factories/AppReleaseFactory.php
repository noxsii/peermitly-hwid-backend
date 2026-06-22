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
        $version = 'v'.fake()->unique()->numerify('#.#.#');

        return [
            'version' => $version,
            'notes' => fake()->optional()->sentence(),
            'file_path' => 'app-releases/peermitly-'.$version.'.exe',
            'file_name' => 'peermitly-'.$version.'.exe',
            'file_size' => fake()->numberBetween(5_000_000, 80_000_000),
        ];
    }
}
