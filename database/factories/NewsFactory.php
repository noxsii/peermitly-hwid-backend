<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\News;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<News>
 */
final class NewsFactory extends Factory
{
    /**
     * @var class-string<News>
     */
    protected $model = News::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 999999),
            'title' => $title,
            'description' => fake()->sentence(12),
            'image_path' => 'news/'.fake()->uuid().'.jpg',
            'content' => $this->richContent(),
            'published_at' => now()->subDays(fake()->numberBetween(0, 60)),
        ];
    }

    public function unpublished(): self
    {
        return $this->state(fn (): array => ['published_at' => null]);
    }

    public function withoutImage(): self
    {
        return $this->state(fn (): array => ['image_path' => null]);
    }

    public function publishedAt(DateTimeInterface $date): self
    {
        return $this->state(fn (): array => ['published_at' => $date]);
    }

    private function richContent(): string
    {
        $paragraphs = collect(range(1, fake()->numberBetween(2, 4)))
            ->map(fn (): string => '<p>'.fake()->paragraph().'</p>')
            ->implode('');

        return <<<HTML
<h2>{$this->faker->sentence(3)}</h2>
{$paragraphs}
HTML;
    }
}
