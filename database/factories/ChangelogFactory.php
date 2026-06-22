<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Changelog;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Changelog>
 */
final class ChangelogFactory extends Factory
{
    /**
     * @var class-string<Changelog>
     */
    protected $model = Changelog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'version' => 'v'.fake()->numerify('#.#.#'),
            'content' => $this->richContent(),
            'published_at' => now()->subDays(fake()->numberBetween(0, 60)),
        ];
    }

    public function unpublished(): self
    {
        return $this->state(fn (): array => ['published_at' => null]);
    }

    /**
     * Pin the entry to a given version label.
     */
    public function version(string $version): self
    {
        return $this->state(fn (): array => ['version' => $version]);
    }

    /**
     * Pin the entry to a given published_at timestamp.
     */
    public function publishedAt(DateTimeInterface $date): self
    {
        return $this->state(fn (): array => ['published_at' => $date]);
    }

    private function richContent(): string
    {
        $bullets = collect(range(1, fake()->numberBetween(2, 4)))
            ->map(fn (): string => '<li>'.fake()->sentence().'</li>')
            ->implode('');

        $code = '<pre><code>curl -X POST peermitly.test/api/license-keys/check \\
    -H "Authorization: Bearer $TOKEN" \\
    -d \'{"key":"PRMT-9F2A-4E11-XR07"}\'</code></pre>';

        return <<<HTML
<h2>{$this->faker->sentence(3)}</h2>
<p>{$this->faker->paragraph()}</p>
<h3>What's new</h3>
<ul>{$bullets}</ul>
<p>Use inline <code>license_keys.check</code> like this:</p>
{$code}
<p><strong>Heads up:</strong> {$this->faker->sentence()}</p>
HTML;
    }
}
