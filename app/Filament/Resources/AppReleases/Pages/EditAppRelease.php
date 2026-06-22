<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Pages;

use App\Filament\Resources\AppReleases\AppReleaseResource;
use App\Filament\Resources\AppReleases\Support\ResolvesReleaseFileMeta;
use App\Models\AppRelease;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditAppRelease extends EditRecord
{
    use ResolvesReleaseFileMeta;

    protected static string $resource = AppReleaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->withFileName($data);
    }

    protected function afterSave(): void
    {
        if ($this->record instanceof AppRelease) {
            $this->syncFileSize($this->record);
        }
    }
}
