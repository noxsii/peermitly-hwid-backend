<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApiTokens\Pages;

use App\Filament\Resources\ApiTokens\ApiTokenResource;
use Filament\Resources\Pages\ListRecords;

final class ListApiTokens extends ListRecords
{
    protected static string $resource = ApiTokenResource::class;
}
