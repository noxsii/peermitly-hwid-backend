<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class AppReleaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('version')
                    ->label('Version')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->placeholder('v1.0.0'),
                FileUpload::make('file_path')
                    ->label('Application file')
                    ->required()
                    ->disk('local')
                    ->directory('app-releases')
                    ->visibility('private')
                    ->preserveFilenames()
                    ->storeFiles()
                    ->maxSize(1024 * 1024) // 1 GB (KB units)
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label('Release notes')
                    ->rows(4)
                    ->maxLength(2000)
                    ->columnSpanFull(),
            ]);
    }
}
