<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApiRequestLogs\Tables;

use App\Models\ApiRequestLog;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class ApiRequestLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
                TextColumn::make('method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GET' => 'info',
                        'POST' => 'success',
                        'PATCH', 'PUT' => 'warning',
                        'DELETE' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('path')
                    ->label('Path')
                    ->fontFamily('mono')
                    ->searchable()
                    ->limit(60)
                    ->copyable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ApiRequestLog $record): string => match (true) {
                        $record->status >= 500 => 'danger',
                        $record->status >= 400 => 'warning',
                        $record->status >= 300 => 'info',
                        $record->status >= 200 => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('User')
                    ->placeholder('guest')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('ip')
                    ->label('IP')
                    ->fontFamily('mono')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('duration_ms')
                    ->label('ms')
                    ->numeric()
                    ->sortable()
                    ->color(fn (?int $state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 1000 => 'danger',
                        $state >= 300 => 'warning',
                        default => 'success',
                    }),
                TextColumn::make('route_name')
                    ->label('Route')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('method')
                    ->label('Method')
                    ->options([
                        'GET' => 'GET',
                        'POST' => 'POST',
                        'PATCH' => 'PATCH',
                        'PUT' => 'PUT',
                        'DELETE' => 'DELETE',
                    ]),
                Filter::make('errors_only')
                    ->label('Errors only (>= 400)')
                    ->query(fn ($query) => $query->where('status', '>=', 400))
                    ->toggle(),
                Filter::make('slow_only')
                    ->label('Slow (>= 1000 ms)')
                    ->query(fn ($query) => $query->where('duration_ms', '>=', 1000))
                    ->toggle(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
