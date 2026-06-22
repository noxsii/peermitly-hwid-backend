<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LicenseKeyGeneratorType;
use App\Models\Builders\LicenseKeyTypeBuilder;
use Database\Factories\LicenseKeyTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property int $team_id
 * @property string $name
 * @property string|null $description
 * @property LicenseKeyGeneratorType $generator_type
 * @property array<array-key, mixed> $configuration
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, LicenseKey> $licenseKeys
 * @property-read int|null $license_keys_count
 * @property-read Team $team
 *
 * @method static LicenseKeyTypeFactory factory($count = null, $state = [])
 * @method static LicenseKeyTypeBuilder|LicenseKeyType newModelQuery()
 * @method static LicenseKeyTypeBuilder|LicenseKeyType newQuery()
 * @method static LicenseKeyTypeBuilder|LicenseKeyType query()
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereActive()
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereConfiguration($value)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereCreatedAt($value)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereDescription($value)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereGeneratorType($value)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereId($value)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereIsActive($value)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereName($value)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereTeam((Team|int) $team)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereTeamId($value)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereUpdatedAt($value)
 * @method static LicenseKeyTypeBuilder|LicenseKeyType whereUuid($value)
 *
 * @mixin Model
 */
#[Fillable('team_id', 'name', 'description', 'generator_type', 'configuration', 'is_active')]
#[UseEloquentBuilder(LicenseKeyTypeBuilder::class)]
final class LicenseKeyType extends Model
{
    /** @use HasFactory<LicenseKeyTypeFactory> */
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
            'name' => 'string',
            'description' => 'string',
            'generator_type' => LicenseKeyGeneratorType::class,
            'configuration' => 'array',
            'is_active' => 'boolean',
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
     * @return HasMany<LicenseKey, $this>
     */
    public function licenseKeys(): HasMany
    {
        return $this->hasMany(LicenseKey::class);
    }
}
