<?php

declare(strict_types=1);

namespace App\Filament\Resources\Backups\Tables;

use App\Models\Backup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;

final class BackupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->description(fn (Backup $record): string => $record->user->email)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('label')
                    ->label('Label')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('machine_guid')
                    ->label('Machine GUID')
                    ->limit(20)
                    ->placeholder('—')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('disks')
                    ->label('Disks')
                    ->badge()
                    ->getStateUsing(function (Backup $record): int {
                        $disks = Arr::get($record->data, 'snapshot.disks', []);

                        return is_array($disks) ? count($disks) : 0;
                    }),
                TextColumn::make('client_created_at')
                    ->label('Created on client')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
