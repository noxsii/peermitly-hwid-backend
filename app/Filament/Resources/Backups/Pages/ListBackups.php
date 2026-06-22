<?php

declare(strict_types=1);

namespace App\Filament\Resources\Backups\Pages;

use App\Filament\Resources\Backups\BackupResource;
use Filament\Resources\Pages\ListRecords;

final class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;
}
