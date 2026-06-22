<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AppReleaseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property string $version
 * @property string $platform
 * @property string|null $notes
 * @property string|null $signature
 * @property string $file_path
 * @property string $file_name
 * @property int $file_size
 * @property Carbon|null $published_at
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static AppReleaseFactory factory($count = null, $state = [])
 * @method static Builder<static>|AppRelease newModelQuery()
 * @method static Builder<static>|AppRelease newQuery()
 * @method static Builder<static>|AppRelease query()
 *
 * @mixin Model
 */
#[Fillable('version', 'platform', 'notes', 'signature', 'file_path', 'file_name', 'file_size', 'published_at', 'is_active')]
final class AppRelease extends Model
{
    /** @use HasFactory<AppReleaseFactory> */
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
            'version' => 'string',
            'platform' => 'string',
            'notes' => 'string',
            'signature' => 'string',
            'file_path' => 'string',
            'file_name' => 'string',
            'file_size' => 'integer',
            'published_at' => 'datetime',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
