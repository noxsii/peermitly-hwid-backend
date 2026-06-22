<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ApiRequestLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property string $method
 * @property string $path
 * @property string|null $route_name
 * @property int $status
 * @property int|null $user_id
 * @property int|null $license_key_id
 * @property string|null $ip
 * @property string|null $user_agent
 * @property int|null $duration_ms
 * @property array<array-key, mixed>|null $request_payload
 * @property Carbon $created_at
 * @property-read LicenseKey|null $licenseKey
 * @property-read User|null $user
 *
 * @method static Builder<static>|ApiRequestLog newModelQuery()
 * @method static Builder<static>|ApiRequestLog newQuery()
 * @method static Builder<static>|ApiRequestLog query()
 * @method static Builder<static>|ApiRequestLog whereCreatedAt($value)
 * @method static Builder<static>|ApiRequestLog whereDurationMs($value)
 * @method static Builder<static>|ApiRequestLog whereId($value)
 * @method static Builder<static>|ApiRequestLog whereIp($value)
 * @method static Builder<static>|ApiRequestLog whereLicenseKeyId($value)
 * @method static Builder<static>|ApiRequestLog whereMethod($value)
 * @method static Builder<static>|ApiRequestLog wherePath($value)
 * @method static Builder<static>|ApiRequestLog whereRequestPayload($value)
 * @method static Builder<static>|ApiRequestLog whereRouteName($value)
 * @method static Builder<static>|ApiRequestLog whereStatus($value)
 * @method static Builder<static>|ApiRequestLog whereUserAgent($value)
 * @method static Builder<static>|ApiRequestLog whereUserId($value)
 * @method static Builder<static>|ApiRequestLog whereUuid($value)
 *
 * @mixin Model
 */
#[Fillable(
    'uuid',
    'method',
    'path',
    'route_name',
    'status',
    'user_id',
    'license_key_id',
    'ip',
    'user_agent',
    'duration_ms',
    'request_payload',
    'created_at',
)]
#[WithoutTimestamps]
final class ApiRequestLog extends Model
{
    /** @use HasFactory<ApiRequestLogFactory> */
    use HasFactory;

    use HasUuids;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'status' => 'integer',
            'user_id' => 'integer',
            'license_key_id' => 'integer',
            'duration_ms' => 'integer',
            'request_payload' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<LicenseKey, $this>
     */
    public function licenseKey(): BelongsTo
    {
        return $this->belongsTo(LicenseKey::class);
    }
}
