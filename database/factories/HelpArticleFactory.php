<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\HelpArticle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<HelpArticle>
 */
final class HelpArticleFactory extends Factory
{
    /**
     * @var class-string<HelpArticle>
     */
    protected $model = HelpArticle::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = ucfirst(fake()->unique()->sentence(4));

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'excerpt' => fake()->sentence(),
            'content' => '<h2>'.fake()->sentence(3).'</h2><p>'.fake()->paragraph().'</p><p>'.fake()->paragraph().'</p>',
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
        ];
    }

    public function unpublished(): self
    {
        return $this->state(fn (): array => ['published_at' => null]);
    }
}
