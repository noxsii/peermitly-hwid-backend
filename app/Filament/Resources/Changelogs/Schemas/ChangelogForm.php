<?php

declare(strict_types=1);

namespace App\Filament\Resources\Changelogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class ChangelogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('version')
                    ->label('Version')
                    ->maxLength(60)
                    ->placeholder('v1.2.0'),
                DateTimePicker::make('published_at')
                    ->label('Published at')
                    ->seconds(false)
                    ->native(false),
                RichEditor::make('content')
                    ->label('Content')
                    ->required()
                    ->columnSpanFull()
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
                        ['h1', 'h2', 'h3', 'small'],
                        ['link', 'highlight'],
                        ['code', 'codeBlock', 'blockquote'],
                        ['bulletList', 'orderedList'],
                        ['table', 'grid', 'attachFiles'],
                        ['undo', 'redo'],
                    ]),
            ]);
    }
}
