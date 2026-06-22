<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeys\Pages;

use App\Filament\Resources\LicenseKeys\LicenseKeyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListLicenseKeys extends ListRecords
{
    protected static string $resource = LicenseKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
