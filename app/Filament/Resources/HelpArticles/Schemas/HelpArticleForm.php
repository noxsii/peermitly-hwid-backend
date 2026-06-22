<?php

declare(strict_types=1);

namespace App\Filament\Resources\HelpArticles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class HelpArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, ?string $state, callable $set): void {
                        if ($operation === 'create' && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->regex('/^[a-z0-9-]+$/')
                    ->helperText('Lowercase letters, numbers and dashes only.'),
                DateTimePicker::make('published_at')
                    ->label('Published at')
                    ->seconds(false)
                    ->native(false)
                    ->helperText('Leave empty to keep as draft.'),
                Textarea::make('excerpt')
                    ->label('Excerpt')
                    ->maxLength(255)
                    ->rows(2)
                    ->columnSpanFull()
                    ->helperText('Short teaser shown in the article list.'),
                RichEditor::make('content')
                    ->label('Content')
                    ->required()
                    ->columnSpanFull()
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike'],
                        ['h1', 'h2', 'h3'],
                        ['link'],
                        ['code', 'codeBlock', 'blockquote'],
                        ['bulletList', 'orderedList'],
                        ['table', 'attachFiles'],
                        ['undo', 'redo'],
                    ]),
            ]);
    }
}
