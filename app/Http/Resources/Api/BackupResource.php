<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Backup
 */
final class BackupResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'client_backup_id' => $this->client_backup_id,
            'label' => $this->label,
            'machine_guid' => $this->machine_guid,
            'client_created_at' => $this->client_created_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'data' => $this->data,
        ];
    }
}
