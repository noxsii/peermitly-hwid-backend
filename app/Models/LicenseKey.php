<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LicenseKeyStatus;
use App\Enums\LicenseValidityUnit;
use App\Models\Builders\LicenseKeyBuilder;
use Database\Factories\LicenseKeyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property string $uuid
 * @property int $team_id
 * @property int $license_key_type_id
 * @property int $product_id
 * @property int|null $customer_id
 * @property int|null $created_by
 * @property string $key
 * @property string $normalized_key
 * @property LicenseKeyStatus $status
 * @property int|null $validity_amount
 * @property LicenseValidityUnit $validity_unit
 * @property int|null $max_activations
 * @property bool $requires_hwid_check
 * @property Carbon|null $activated_at
 * @property Carbon|null $expires_at
 * @property Carbon|null $last_checked_at
 * @property int $check_count
 * @property Carbon|null $revoked_at
 * @property string|null $revoked_reason
 * @property array<array-key, mixed>|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, LicenseKeyActivation> $activations
 * @property-read int|null $activations_count
 * @property-read Collection<int, LicenseKeyCheck> $checks
 * @property-read int|null $checks_count
 * @property-read User|null $creator
 * @property-read Customer|null $customer
 * @property-read Product $product
 * @property-read Team $team
 * @property-read LicenseKeyType $type
 *
 * @method static LicenseKeyFactory factory($count = null, $state = [])
 * @method static LicenseKeyBuilder|LicenseKey newModelQuery()
 * @method static LicenseKeyBuilder|LicenseKey newQuery()
 * @method static LicenseKeyBuilder|LicenseKey query()
 * @method static LicenseKeyBuilder|LicenseKey whereActivatedAt($value)
 * @method static LicenseKeyBuilder|LicenseKey whereActive()
 * @method static LicenseKeyBuilder|LicenseKey whereCheckCount($value)
 * @method static LicenseKeyBuilder|LicenseKey whereCreatedAt($value)
 * @method static LicenseKeyBuilder|LicenseKey whereCreatedBy($value)
 * @method static LicenseKeyBuilder|LicenseKey whereCustomerId($value)
 * @method static LicenseKeyBuilder|LicenseKey whereExpired()
 * @method static LicenseKeyBuilder|LicenseKey whereExpiresAt($value)
 * @method static LicenseKeyBuilder|LicenseKey whereForProduct((Product|int) $product)
 * @method static LicenseKeyBuilder|LicenseKey whereId($value)
 * @method static LicenseKeyBuilder|LicenseKey whereKey($value)
 * @method static LicenseKeyBuilder|LicenseKey whereLastCheckedAt($value)
 * @method static LicenseKeyBuilder|LicenseKey whereLicenseKeyTypeId($value)
 * @method static LicenseKeyBuilder|LicenseKey whereMaxActivations($value)
 * @method static LicenseKeyBuilder|LicenseKey whereMetadata($value)
 * @method static LicenseKeyBuilder|LicenseKey whereNormalizedKey($value)
 * @method static LicenseKeyBuilder|LicenseKey wherePending()
 * @method static LicenseKeyBuilder|LicenseKey whereProductId($value)
 * @method static LicenseKeyBuilder|LicenseKey whereRequiresHwidCheck($value)
 * @method static LicenseKeyBuilder|LicenseKey whereRevoked()
 * @method static LicenseKeyBuilder|LicenseKey whereRevokedAt($value)
 * @method static LicenseKeyBuilder|LicenseKey whereRevokedReason($value)
 * @method static LicenseKeyBuilder|LicenseKey whereStatus($value)
 * @method static LicenseKeyBuilder|LicenseKey whereTeam((Team|int) $team)
 * @method static LicenseKeyBuilder|LicenseKey whereTeamId($value)
 * @method static LicenseKeyBuilder|LicenseKey whereUpdatedAt($value)
 * @method static LicenseKeyBuilder|LicenseKey whereUuid($value)
 * @method static LicenseKeyBuilder|LicenseKey whereValidityAmount($value)
 * @method static LicenseKeyBuilder|LicenseKey whereValidityUnit($value)
 *
 * @mixin Model
 */
#[Fillable(
    'team_id',
    'license_key_type_id',
    'product_id',
    'customer_id',
    'created_by',
    'key',
    'normalized_key',
    'status',
    'validity_amount',
    'validity_unit',
    'max_activations',
    'requires_hwid_check',
    'activated_at',
    'expires_at',
    'last_checked_at',
    'check_count',
    'revoked_at',
    'revoked_reason',
    'metadata',
)]
#[UseEloquentBuilder(LicenseKeyBuilder::class)]
final class LicenseKey extends Model
{
    /** @use HasFactory<LicenseKeyFactory> */
    use HasFactory;

    use HasUuids;
    use LogsActivity;
    use Searchable;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status',
                'customer_id',
                'max_activations',
                'requires_hwid_check',
                'validity_amount',
                'validity_unit',
                'activated_at',
                'expires_at',
                'revoked_at',
                'revoked_reason',
                'metadata',
            ])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('license_key');
    }

    /**
     * @return MorphMany<Activity, $this>
     */
    public function activities(): MorphMany
    {
        return $this->activitiesAsSubject();
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'key' => $this->key,
            'normalized_key' => $this->normalized_key,
        ];
    }

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
            'license_key_type_id' => 'integer',
            'product_id' => 'integer',
            'customer_id' => 'integer',
            'created_by' => 'integer',
            'key' => 'string',
            'normalized_key' => 'string',
            'status' => LicenseKeyStatus::class,
            'validity_amount' => 'integer',
            'validity_unit' => LicenseValidityUnit::class,
            'max_activations' => 'integer',
            'requires_hwid_check' => 'boolean',
            'activated_at' => 'datetime',
            'expires_at' => 'datetime',
            'last_checked_at' => 'datetime',
            'check_count' => 'integer',
            'revoked_at' => 'datetime',
            'revoked_reason' => 'string',
            'metadata' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo<LicenseKeyType, $this>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(LicenseKeyType::class, 'license_key_type_id');
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return HasMany<LicenseKeyCheck, $this>
     */
    public function checks(): HasMany
    {
        return $this->hasMany(LicenseKeyCheck::class);
    }

    /**
     * @return HasMany<LicenseKeyActivation, $this>
     */
    public function activations(): HasMany
    {
        return $this->hasMany(LicenseKeyActivation::class);
    }

    public function isPending(): bool
    {
        return $this->status === LicenseKeyStatus::PENDING;
    }

    public function isActive(): bool
    {
        return $this->status === LicenseKeyStatus::ACTIVE;
    }

    public function isLifetime(): bool
    {
        return $this->validity_unit === LicenseValidityUnit::LIFETIME;
    }
}
