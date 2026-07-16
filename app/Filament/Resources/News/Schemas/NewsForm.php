<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class NewsForm
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
                TextInput::make('slug')
                    ->label('Slug')
                    ->helperText('Leave empty to generate from the title.')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                DateTimePicker::make('published_at')
                    ->label('Published at')
                    ->seconds(false)
                    ->native(false),
                Textarea::make('description')
                    ->label('Description')
                    ->helperText('Short summary shown on the overview cards.')
                    ->required()
                    ->maxLength(500)
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('news')
                    ->columnSpanFull(),
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
