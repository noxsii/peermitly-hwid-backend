<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Support;

use App\Models\AppRelease;
use Illuminate\Support\Facades\Storage;

trait ResolvesReleaseFileMeta
{
    /**
     * Set the original file name from the uploaded path. Runs before the record
     * is written so the (non-nullable) column is populated.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function withFileName(array $data): array
    {
        $path = is_string($data['file_path'] ?? null) ? $data['file_path'] : '';
        $data['file_name'] = $path !== '' ? basename($path) : '';

        return $data;
    }

    /**
     * Persist the byte size once the upload is physically on disk (after the
     * record has been saved).
     */
    protected function syncFileSize(AppRelease $record): void
    {
        $disk = Storage::disk('local');

        $record->update([
            'file_size' => $disk->exists($record->file_path) ? $disk->size($record->file_path) : 0,
        ]);
    }
}
