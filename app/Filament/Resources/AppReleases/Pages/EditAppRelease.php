<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Pages;

use App\Filament\Resources\AppReleases\AppReleaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditAppRelease extends EditRecord
{
    protected static string $resource = AppReleaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
