<?php

declare(strict_types=1);

use App\Contracts\SystemHealthChecker;
use App\Data\Health\HealthCheckResult;
use App\Data\Health\SystemHealthData;
use App\Enums\HealthStatus;
use Mockery\MockInterface;

test('health endpoint returns 200 with ok status when everything is up', function (): void {
    $this->mock(SystemHealthChecker::class, function (MockInterface $mock): void {
        $mock->shouldReceive('handle')->once()->andReturn(new SystemHealthData(
            status: HealthStatus::OK,
            checkedAt: now(),
            checks: [
                new HealthCheckResult('database', HealthStatus::OK, 4),
                new HealthCheckResult('cache', HealthStatus::OK, 1),
                new HealthCheckResult('redis', HealthStatus::OK, 1),
            ],
        ));
    });

    $this->getJson('/api/health')
        ->assertOk()
        ->assertJsonStructure(['status', 'checked_at', 'checks' => [['name', 'status', 'latency_ms', 'message']]])
        ->assertJsonPath('status', 'ok')
        ->assertJsonPath('checks.0.name', 'database')
        ->assertJsonPath('checks.0.status', 'ok');
});

test('health endpoint returns 503 when any dependency is down', function (): void {
    $this->mock(SystemHealthChecker::class, function (MockInterface $mock): void {
        $mock->shouldReceive('handle')->once()->andReturn(new SystemHealthData(
            status: HealthStatus::DOWN,
            checkedAt: now(),
            checks: [
                new HealthCheckResult('database', HealthStatus::DOWN, 12, 'connection refused'),
                new HealthCheckResult('cache', HealthStatus::OK, 1),
                new HealthCheckResult('redis', HealthStatus::OK, 1),
            ],
        ));
    });

    $this->getJson('/api/health')
        ->assertStatus(503)
        ->assertJsonPath('status', 'down')
        ->assertJsonPath('checks.0.message', 'connection refused');
});

test('health endpoint stays 200 when status is degraded', function (): void {
    $this->mock(SystemHealthChecker::class, function (MockInterface $mock): void {
        $mock->shouldReceive('handle')->once()->andReturn(new SystemHealthData(
            status: HealthStatus::DEGRADED,
            checkedAt: now(),
            checks: [
                new HealthCheckResult('database', HealthStatus::OK, 4),
                new HealthCheckResult('cache', HealthStatus::DEGRADED, 250, 'slow response'),
                new HealthCheckResult('redis', HealthStatus::OK, 1),
            ],
        ));
    });

    $this->getJson('/api/health')
        ->assertOk()
        ->assertJsonPath('status', 'degraded');
});

test('health endpoint is unauthenticated', function (): void {
    $this->getJson('/api/health')->assertOk();
});

test('health endpoint computes aggregate status from real services', function (): void {
    $this->getJson('/api/health')
        ->assertOk()
        ->assertJsonPath('status', 'ok')
        ->assertJsonCount(3, 'checks');
});
