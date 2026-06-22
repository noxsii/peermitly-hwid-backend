<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                    ->placeholder('0.2.0'),
                TextInput::make('platform')
                    ->label('Platform')
                    ->required()
                    ->default('windows-x86_64')
                    ->maxLength(50),
                FileUpload::make('file_path')
                    ->label('Installer (.exe / setup file)')
                    ->required()
                    ->disk('local')
                    ->directory('app-releases')
                    ->visibility('private')
                    ->preserveFilenames()
                    ->storeFiles()
                    ->maxSize(1024 * 1024) // 1 GB (KB units)
                    ->columnSpanFull(),
                Textarea::make('signature')
                    ->label('Updater signature (.sig content)')
                    ->helperText('Paste the full contents of the generated .sig file. Required for Tauri auto-updates.')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label('Release notes')
                    ->rows(4)
                    ->maxLength(2000)
                    ->columnSpanFull(),
                DateTimePicker::make('published_at')
                    ->label('Published at')
                    ->seconds(false)
                    ->native(false)
                    ->default(now())
                    ->helperText('Only published, active versions are offered to clients.'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
