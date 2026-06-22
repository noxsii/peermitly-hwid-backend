<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeys\Pages;

use App\Filament\Resources\LicenseKeys\LicenseKeyResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLicenseKey extends CreateRecord
{
    protected static string $resource = LicenseKeyResource::class;
}
