<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeys\Pages;

use App\Filament\Resources\LicenseKeys\LicenseKeyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditLicenseKey extends EditRecord
{
    protected static string $resource = LicenseKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
