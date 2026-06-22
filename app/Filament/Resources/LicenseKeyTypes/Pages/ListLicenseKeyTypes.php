<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeyTypes\Pages;

use App\Filament\Resources\LicenseKeyTypes\LicenseKeyTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListLicenseKeyTypes extends ListRecords
{
    protected static string $resource = LicenseKeyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
