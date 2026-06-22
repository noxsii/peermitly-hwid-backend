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
 * @property string|null $notes
 * @property string $file_path
 * @property string $file_name
 * @property int $file_size
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
#[Fillable('version', 'notes', 'file_path', 'file_name', 'file_size')]
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
            'notes' => 'string',
            'file_path' => 'string',
            'file_name' => 'string',
            'file_size' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
