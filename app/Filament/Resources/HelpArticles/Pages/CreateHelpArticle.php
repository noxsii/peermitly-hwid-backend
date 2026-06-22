<?php

declare(strict_types=1);

namespace App\Filament\Resources\HelpArticles\Pages;

use App\Filament\Resources\HelpArticles\HelpArticleResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateHelpArticle extends CreateRecord
{
    protected static string $resource = HelpArticleResource::class;
}
