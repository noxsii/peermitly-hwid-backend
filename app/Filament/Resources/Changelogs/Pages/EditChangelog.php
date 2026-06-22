<?php

declare(strict_types=1);

namespace App\Filament\Resources\Changelogs\Pages;

use App\Filament\Resources\Changelogs\ChangelogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditChangelog extends EditRecord
{
    protected static string $resource = ChangelogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
