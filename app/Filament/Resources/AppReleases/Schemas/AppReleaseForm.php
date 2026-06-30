<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Schemas;

use App\Enums\ReleasePlatform;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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
                    ->helperText('Muss exakt der Tauri-App-Version entsprechen, z. B. 0.2.0.')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Textarea::make('notes')
                    ->label('Release Notes')
                    ->helperText('Wird im Update-Dialog der App angezeigt.')
                    ->rows(4)
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label('Veröffentlicht')
                    ->helperText('Nur veröffentlichte Versionen werden an die App ausgeliefert.')
                    ->default(false),
                DateTimePicker::make('published_at')
                    ->label('Veröffentlicht am')
                    ->helperText('Die neueste veröffentlichte Version wird ausgeliefert.')
                    ->default(now()),
                Repeater::make('platforms')
                    ->label('Plattformen')
                    ->helperText('Universal-macOS-Build: je einen Eintrag für darwin-aarch64 und darwin-x86_64 mit derselben Datei und Signatur.')
                    ->schema([
                        Select::make('platform')
                            ->label('Plattform')
                            ->options(self::platformOptions())
                            ->required(),
                        FileUpload::make('path')
                            ->label('Datei (Bundle)')
                            ->disk('public')
                            ->directory('releases')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->maxSize(512000)
                            ->helperText('Das vom Tauri-Build erzeugte Update-Artefakt (.app.tar.gz, .msi.zip, .nsis.zip).')
                            ->required(),
                        Textarea::make('signature')
                            ->label('Signatur')
                            ->helperText('Inhalt der zugehörigen .sig-Datei.')
                            ->rows(3)
                            ->required(),
                    ])
                    ->itemLabel(fn (array $state): ?string => is_string($state['platform'] ?? null) ? $state['platform'] : null)
                    ->minItems(1)
                    ->columns(1)
                    ->columnSpanFull()
                    ->addActionLabel('Plattform hinzufügen'),
            ]);
    }

    /**
     * @return array<string, string>
     */
    private static function platformOptions(): array
    {
        $options = [];

        foreach (ReleasePlatform::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
