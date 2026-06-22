<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
final class CustomerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'email' => fake()->unique()->safeEmail(),
            'name' => fake()->name(),
            'company' => fake()->optional()->company(),
            'metadata' => null,
        ];
    }

    public function forTeam(Team $team): self
    {
        return $this->state(fn (): array => ['team_id' => $team->id]);
    }
}
