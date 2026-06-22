<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LicenseKeyActivationFactory;
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
 * @property int $team_id
 * @property int $license_key_id
 * @property string $machine_id
 * @property string|null $hostname
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon $activated_at
 * @property Carbon|null $last_seen_at
 * @property Carbon|null $revoked_at
 * @property array<array-key, mixed>|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read LicenseKey $licenseKey
 *
 * @method static LicenseKeyActivationFactory factory($count = null, $state = [])
 * @method static Builder<static>|LicenseKeyActivation newModelQuery()
 * @method static Builder<static>|LicenseKeyActivation newQuery()
 * @method static Builder<static>|LicenseKeyActivation query()
 * @method static Builder<static>|LicenseKeyActivation whereActivatedAt($value)
 * @method static Builder<static>|LicenseKeyActivation whereCreatedAt($value)
 * @method static Builder<static>|LicenseKeyActivation whereHostname($value)
 * @method static Builder<static>|LicenseKeyActivation whereId($value)
 * @method static Builder<static>|LicenseKeyActivation whereIpAddress($value)
 * @method static Builder<static>|LicenseKeyActivation whereLastSeenAt($value)
 * @method static Builder<static>|LicenseKeyActivation whereLicenseKeyId($value)
 * @method static Builder<static>|LicenseKeyActivation whereMachineId($value)
 * @method static Builder<static>|LicenseKeyActivation whereMetadata($value)
 * @method static Builder<static>|LicenseKeyActivation whereRevokedAt($value)
 * @method static Builder<static>|LicenseKeyActivation whereTeamId($value)
 * @method static Builder<static>|LicenseKeyActivation whereUpdatedAt($value)
 * @method static Builder<static>|LicenseKeyActivation whereUserAgent($value)
 * @method static Builder<static>|LicenseKeyActivation whereUuid($value)
 *
 * @mixin Model
 */
#[Fillable(
    'team_id',
    'license_key_id',
    'machine_id',
    'hostname',
    'ip_address',
    'user_agent',
    'activated_at',
    'last_seen_at',
    'revoked_at',
    'metadata',
)]
final class LicenseKeyActivation extends Model
{
    /** @use HasFactory<LicenseKeyActivationFactory> */
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
            'machine_id' => 'string',
            'hostname' => 'string',
            'ip_address' => 'string',
            'user_agent' => 'string',
            'activated_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'revoked_at' => 'datetime',
            'metadata' => 'array',
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
}
