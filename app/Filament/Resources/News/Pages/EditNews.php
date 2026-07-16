<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Pages;

use App\Actions\News\GenerateNewsSlugAction;
use App\Filament\Resources\News\NewsResource;
use App\Models\News;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    /**
     * @return array<int, \Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['slug'])) {
            $title = is_string($data['title'] ?? null) ? $data['title'] : '';
            $record = $this->record;
            $ignoreId = $record instanceof News ? $record->id : null;
            $data['slug'] = app(GenerateNewsSlugAction::class)->handle($title, $ignoreId);
        }

        return $data;
    }
}
