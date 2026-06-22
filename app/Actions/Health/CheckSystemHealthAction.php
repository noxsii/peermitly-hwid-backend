<?php

declare(strict_types=1);

namespace App\Actions\Health;

use App\Contracts\SystemHealthChecker;
use App\Data\Health\HealthCheckResult;
use App\Data\Health\SystemHealthData;
use App\Enums\HealthStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

final readonly class CheckSystemHealthAction implements SystemHealthChecker
{
    public function handle(): SystemHealthData
    {
        $checks = [
            $this->checkDatabase(),
            $this->checkCache(),
            $this->checkRedis(),
        ];

        $status = HealthStatus::OK;
        foreach ($checks as $check) {
            if ($check->status === HealthStatus::DOWN) {
                $status = HealthStatus::DOWN;
                break;
            }
            if ($check->status === HealthStatus::DEGRADED) {
                $status = HealthStatus::DEGRADED;
            }
        }

        return new SystemHealthData(
            status: $status,
            checkedAt: now(),
            checks: $checks,
        );
    }

    private function checkDatabase(): HealthCheckResult
    {
        return $this->time('database', static function (): void {
            DB::connection()->select('select 1');
        });
    }

    private function checkCache(): HealthCheckResult
    {
        return $this->time('cache', static function (): void {
            $key = 'health:'.Str::random(8);
            Cache::put($key, 'ok', 10);
            $value = Cache::get($key);
            Cache::forget($key);

            throw_if($value !== 'ok', RuntimeException::class, 'Cache read/write mismatch.');
        });
    }

    private function checkRedis(): HealthCheckResult
    {
        return $this->time('redis', static function (): void {
            Redis::connection()->command('ping');
        });
    }

    private function time(string $name, callable $callback): HealthCheckResult
    {
        $start = hrtime(true);

        try {
            $callback();

            return new HealthCheckResult(
                name: $name,
                status: HealthStatus::OK,
                latencyMs: $this->elapsedMs($start),
            );
        } catch (Throwable $throwable) {
            return new HealthCheckResult(
                name: $name,
                status: HealthStatus::DOWN,
                latencyMs: $this->elapsedMs($start),
                message: $throwable->getMessage(),
            );
        }
    }

    private function elapsedMs(int $start): int
    {
        return (int) round((hrtime(true) - $start) / 1_000_000);
    }
}
