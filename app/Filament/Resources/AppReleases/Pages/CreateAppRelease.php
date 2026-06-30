<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Pages;

use App\Filament\Resources\AppReleases\AppReleaseResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAppRelease extends CreateRecord
{
    protected static string $resource = AppReleaseResource::class;
}
