<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Pages;

use App\Filament\Resources\AppReleases\AppReleaseResource;
use App\Filament\Resources\AppReleases\Support\ResolvesReleaseFileMeta;
use App\Models\AppRelease;
use Filament\Resources\Pages\CreateRecord;

final class CreateAppRelease extends CreateRecord
{
    use ResolvesReleaseFileMeta;

    protected static string $resource = AppReleaseResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->withFileName($data);
    }

    protected function afterCreate(): void
    {
        if ($this->record instanceof AppRelease) {
            $this->syncFileSize($this->record);
        }
    }
}
