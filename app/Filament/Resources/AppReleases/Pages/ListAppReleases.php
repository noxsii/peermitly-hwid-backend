<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Pages;

use App\Filament\Resources\AppReleases\AppReleaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListAppReleases extends ListRecords
{
    protected static string $resource = AppReleaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Upload version'),
        ];
    }
}
