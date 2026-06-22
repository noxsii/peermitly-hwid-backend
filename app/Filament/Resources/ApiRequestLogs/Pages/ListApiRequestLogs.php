<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApiRequestLogs\Pages;

use App\Filament\Resources\ApiRequestLogs\ApiRequestLogResource;
use Filament\Resources\Pages\ListRecords;

final class ListApiRequestLogs extends ListRecords
{
    protected static string $resource = ApiRequestLogResource::class;
}
