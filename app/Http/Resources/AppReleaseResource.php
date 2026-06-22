<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\AppRelease;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AppRelease
 */
final class AppReleaseResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'version' => $this->version,
            'notes' => $this->notes,
            'file_name' => $this->file_name,
            'file_size' => $this->file_size,
            'created_at' => $this->created_at?->toIso8601String(),
            'download_url' => route('downloads.download', $this->resource),
        ];
    }
}
