<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LicenseCheckStatus;
use Database\Factories\LicenseKeyCheckFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property int|null $team_id
 * @property int|null $license_key_id
 * @property int|null $product_id
 * @property LicenseCheckStatus $status
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $hwid
 * @property array<array-key, mixed>|null $request_payload
 * @property array<array-key, mixed>|null $response_payload
 * @property Carbon $checked_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read LicenseKey|null $licenseKey
 * @property-read Product|null $product
 *
 * @method static LicenseKeyCheckFactory factory($count = null, $state = [])
 * @method static Builder<static>|LicenseKeyCheck newModelQuery()
 * @method static Builder<static>|LicenseKeyCheck newQuery()
 * @method static Builder<static>|LicenseKeyCheck query()
 * @method static Builder<static>|LicenseKeyCheck whereCheckedAt($value)
 * @method static Builder<static>|LicenseKeyCheck whereCreatedAt($value)
 * @method static Builder<static>|LicenseKeyCheck whereHwid($value)
 * @method static Builder<static>|LicenseKeyCheck whereId($value)
 * @method static Builder<static>|LicenseKeyCheck whereIpAddress($value)
 * @method static Builder<static>|LicenseKeyCheck whereLicenseKeyId($value)
 * @method static Builder<static>|LicenseKeyCheck whereProductId($value)
 * @method static Builder<static>|LicenseKeyCheck whereRequestPayload($value)
 * @method static Builder<static>|LicenseKeyCheck whereResponsePayload($value)
 * @method static Builder<static>|LicenseKeyCheck whereStatus($value)
 * @method static Builder<static>|LicenseKeyCheck whereTeamId($value)
 * @method static Builder<static>|LicenseKeyCheck whereUpdatedAt($value)
 * @method static Builder<static>|LicenseKeyCheck whereUserAgent($value)
 * @method static Builder<static>|LicenseKeyCheck whereUuid($value)
 *
 * @mixin Model
 */
#[Fillable(
    'team_id',
    'license_key_id',
    'product_id',
    'status',
    'ip_address',
    'user_agent',
    'hwid',
    'request_payload',
    'response_payload',
    'checked_at',
)]
final class LicenseKeyCheck extends Model
{
    /** @use HasFactory<LicenseKeyCheckFactory> */
    use HasFactory;

    use HasUuids;

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'team_id' => 'integer',
            'license_key_id' => 'integer',
            'product_id' => 'integer',
            'status' => LicenseCheckStatus::class,
            'ip_address' => 'string',
            'user_agent' => 'string',
            'hwid' => 'string',
            'request_payload' => 'array',
            'response_payload' => 'array',
            'checked_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<LicenseKey, $this>
     */
    public function licenseKey(): BelongsTo
    {
        return $this->belongsTo(LicenseKey::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
