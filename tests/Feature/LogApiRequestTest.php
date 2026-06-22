<?php

declare(strict_types=1);

use App\Actions\Logging\StoreApiRequestLogAction;
use App\Data\Logging\ApiRequestLogData;
use App\Jobs\LogApiRequestJob;
use App\Models\ApiRequestLog;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;

test('api request dispatches log job with request details', function (): void {
    Queue::fake();

    $this->getJson('/api/user');

    Queue::assertPushed(LogApiRequestJob::class, fn (LogApiRequestJob $job): bool => $job->data->path === 'api/user'
        && $job->data->method === 'GET'
        && $job->data->status === 401);
});

test('job persists log row via action', function (): void {
    $user = User::factory()->create();

    $data = new ApiRequestLogData(
        method: 'POST',
        path: 'api/license-keys/check',
        routeName: 'license-keys.check',
        status: 200,
        userId: $user->id,
        licenseKeyId: null,
        ip: '127.0.0.1',
        userAgent: 'PestTest/1.0',
        durationMs: 42,
        requestPayload: ['license_key' => 'abc-123'],
        createdAt: Date::now(),
    );

    new LogApiRequestJob($data)->handle(resolve(StoreApiRequestLogAction::class));

    expect(ApiRequestLog::query()->count())->toBe(1);

    $log = ApiRequestLog::query()->first();

    expect($log->method)->toBe('POST');
    expect($log->path)->toBe('api/license-keys/check');
    expect($log->route_name)->toBe('license-keys.check');
    expect($log->status)->toBe(200);
    expect($log->user_id)->toBe($user->id);
    expect($log->duration_ms)->toBe(42);
    expect($log->request_payload)->toBe(['license_key' => 'abc-123']);
    expect($log->uuid)->not->toBeNull();
});
