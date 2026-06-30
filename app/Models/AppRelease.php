<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AppReleaseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $version
 * @property string|null $notes
 * @property list<array{platform: string, path: string, signature: string}> $platforms
 * @property bool $is_published
 * @property Carbon|null $published_at
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
#[Fillable('version', 'notes', 'platforms', 'is_published', 'published_at')]
final class AppRelease extends Model
{
    /** @use HasFactory<AppReleaseFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'version' => 'string',
            'notes' => 'string',
            'platforms' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
