<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Pages;

use App\Actions\News\GenerateNewsSlugAction;
use App\Filament\Resources\News\NewsResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['slug'])) {
            $title = is_string($data['title'] ?? null) ? $data['title'] : '';
            $data['slug'] = app(GenerateNewsSlugAction::class)->handle($title);
        }

        return $data;
    }
}
