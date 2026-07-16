<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\NewsObserver;
use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string|null $image_path
 * @property string $content
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static NewsFactory factory($count = null, $state = [])
 * @method static Builder<static>|News newModelQuery()
 * @method static Builder<static>|News newQuery()
 * @method static Builder<static>|News query()
 *
 * @mixin Model
 */
#[ObservedBy(NewsObserver::class)]
#[Fillable('slug', 'title', 'description', 'image_path', 'content', 'published_at')]
final class News extends Model
{
    /** @use HasFactory<NewsFactory> */
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
        return 'slug';
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'slug' => 'string',
            'title' => 'string',
            'description' => 'string',
            'image_path' => 'string',
            'content' => 'string',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
