<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeyTypes\Pages;

use App\Filament\Resources\LicenseKeyTypes\LicenseKeyTypeResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLicenseKeyType extends CreateRecord
{
    protected static string $resource = LicenseKeyTypeResource::class;
}
