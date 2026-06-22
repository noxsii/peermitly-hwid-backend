<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeyTypes\Pages;

use App\Filament\Resources\LicenseKeyTypes\LicenseKeyTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditLicenseKeyType extends EditRecord
{
    protected static string $resource = LicenseKeyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
