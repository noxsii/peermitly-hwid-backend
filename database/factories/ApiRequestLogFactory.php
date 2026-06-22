<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ApiRequestLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApiRequestLog>
 */
final class ApiRequestLogFactory extends Factory
{
    /**
     * @var class-string<ApiRequestLog>
     */
    protected $model = ApiRequestLog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'method' => fake()->randomElement(['GET', 'POST', 'PATCH', 'DELETE']),
            'path' => '/api/'.fake()->slug(2),
            'route_name' => null,
            'status' => fake()->randomElement([200, 201, 204, 400, 401, 403, 404, 500]),
            'user_id' => null,
            'license_key_id' => null,
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'duration_ms' => fake()->numberBetween(5, 1500),
            'request_payload' => null,
            'created_at' => now(),
        ];
    }
}
