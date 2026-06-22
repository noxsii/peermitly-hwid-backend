<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ChangelogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string|null $version
 * @property string $content
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static ChangelogFactory factory($count = null, $state = [])
 * @method static Builder<static>|Changelog newModelQuery()
 * @method static Builder<static>|Changelog newQuery()
 * @method static Builder<static>|Changelog query()
 * @method static Builder<static>|Changelog whereContent($value)
 * @method static Builder<static>|Changelog whereCreatedAt($value)
 * @method static Builder<static>|Changelog whereId($value)
 * @method static Builder<static>|Changelog wherePublishedAt($value)
 * @method static Builder<static>|Changelog whereTitle($value)
 * @method static Builder<static>|Changelog whereUpdatedAt($value)
 * @method static Builder<static>|Changelog whereUuid($value)
 * @method static Builder<static>|Changelog whereVersion($value)
 *
 * @mixin Model
 */
#[Fillable('title', 'version', 'content', 'published_at')]
final class Changelog extends Model
{
    /** @use HasFactory<ChangelogFactory> */
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
            'title' => 'string',
            'version' => 'string',
            'content' => 'string',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
