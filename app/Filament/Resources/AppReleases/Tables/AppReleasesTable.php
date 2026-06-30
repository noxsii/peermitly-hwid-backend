<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Tables;

use App\Models\AppRelease;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

final class AppReleasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('version')
                    ->label('Version')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Veröffentlicht')
                    ->boolean(),
                TextColumn::make('platforms')
                    ->label('Plattformen')
                    ->badge()
                    ->state(fn (AppRelease $record): array => array_map(
                        static fn (array $entry): string => $entry['platform'],
                        $record->platforms,
                    )),
                TextColumn::make('published_at')
                    ->label('Veröffentlicht am')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Erstellt am')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Veröffentlicht'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
    }
}
