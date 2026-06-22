<?php

declare(strict_types=1);

namespace App\Filament\Resources\HelpArticles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

final class HelpArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(60),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->fontFamily('mono')
                    ->copyable()
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('published_at')
                    ->label('Published')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => $record->published_at !== null),
                TextColumn::make('published_at')
                    ->label('Published at')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('Draft')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                TernaryFilter::make('published')
                    ->label('Published')
                    ->queries(
                        true: fn ($q) => $q->whereNotNull('published_at'),
                        false: fn ($q) => $q->whereNull('published_at'),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
