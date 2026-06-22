<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Data\Logging\ApiRequestLogData;
use App\Jobs\LogApiRequestJob;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\Response;

final class LogApiRequest
{
    private const string REDACT_PLACEHOLDER = '***';

    /**
     * @var array<int, string>
     */
    private const array REDACT_KEYS = [
        'password',
        'password_confirmation',
        'token',
        'access_token',
        'api_token',
        'secret',
        'authorization',
        'key',
    ];

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('api_log_start', microtime(true));

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $start = $request->attributes->get('api_log_start');
        $duration = is_float($start) ? (int) round((microtime(true) - $start) * 1000) : null;

        $user = $request->user();

        dispatch(new LogApiRequestJob(new ApiRequestLogData(
            method: $request->getMethod(),
            path: $request->path(),
            routeName: $request->route()?->getName(),
            status: $response->getStatusCode(),
            userId: $user instanceof User ? $user->id : null,
            licenseKeyId: null,
            ip: $request->ip(),
            userAgent: mb_substr((string) $request->userAgent(), 0, 512) ?: null,
            durationMs: $duration,
            requestPayload: $this->payload($request),
            createdAt: Date::now(),
        )));
    }

    /**
     * @return array<string, mixed>|null
     */
    private function payload(Request $request): ?array
    {
        $data = $request->except(self::REDACT_KEYS);

        if ($data === []) {
            return null;
        }

        return $this->redact($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function redact(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array(mb_strtolower((string) $key), self::REDACT_KEYS, true)) {
                $data[$key] = self::REDACT_PLACEHOLDER;

                continue;
            }

            if (is_array($value)) {
                $data[$key] = $this->redact($value);
            }
        }

        return $data;
    }
}
