<?php

declare(strict_types=1);

namespace App\Actions\Backups;

use App\Models\Backup;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;

final readonly class StoreBackupAction
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function handle(User $user, array $payload): Backup
    {
        $clientId = $payload['id'] ?? '';
        $clientBackupId = is_string($clientId) ? $clientId : '';

        $machineGuid = Arr::get($payload, 'snapshot.machine_guid');
        $label = Arr::get($payload, 'label');
        $createdAtMs = Arr::get($payload, 'created_at');

        return Backup::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'client_backup_id' => $clientBackupId,
            ],
            [
                'label' => is_string($label) ? $label : null,
                'machine_guid' => is_string($machineGuid) ? $machineGuid : null,
                'client_created_at' => is_numeric($createdAtMs)
                    ? Date::createFromTimestampMs((int) $createdAtMs)
                    : null,
                'data' => $payload,
            ],
        );
    }
}
