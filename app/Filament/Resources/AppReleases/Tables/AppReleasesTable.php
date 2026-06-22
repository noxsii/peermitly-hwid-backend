<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases\Tables;

use App\Models\AppRelease;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
                TextColumn::make('platform')
                    ->label('Platform')
                    ->badge()
                    ->color('gray'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                IconColumn::make('signature')
                    ->label('Signed')
                    ->boolean()
                    ->getStateUsing(fn (AppRelease $record): bool => filled($record->signature)),
                TextColumn::make('file_name')
                    ->label('File')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('file_size')
                    ->label('Size')
                    ->formatStateUsing(fn (int $state): string => number_format($state / 1_048_576, 1).' MB')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (AppRelease $record): bool => Storage::disk('local')->exists($record->file_path))
                    ->action(fn (AppRelease $record): StreamedResponse => Storage::disk('local')->download($record->file_path, $record->file_name)),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
