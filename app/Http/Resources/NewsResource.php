<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin News
 */
final class NewsResource extends JsonResource
{
    /**
     * @var string|null
     */
    public static $wrap;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->image_path !== null
                ? Storage::disk('public')->url($this->image_path)
                : null,
            'content' => $this->content,
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
