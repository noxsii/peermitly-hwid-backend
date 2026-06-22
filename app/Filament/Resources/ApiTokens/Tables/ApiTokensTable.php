<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApiTokens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\PersonalAccessToken;

final class ApiTokensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tokenable.name')
                    ->label('Owner')
                    ->description(function (PersonalAccessToken $record): ?string {
                        $email = $record->tokenable?->getAttribute('email');

                        return is_string($email) ? $email : null;
                    })
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('abilities')
                    ->label('Abilities')
                    ->badge()
                    ->placeholder('—'),
                IconColumn::make('used')
                    ->label('Used')
                    ->boolean()
                    ->getStateUsing(fn (PersonalAccessToken $record): bool => $record->last_used_at !== null),
                TextColumn::make('last_used_at')
                    ->label('Last used')
                    ->dateTime('M j, Y H:i')
                    ->since()
                    ->placeholder('Never')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('Never')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('used')
                    ->label('Usage')
                    ->placeholder('All tokens')
                    ->trueLabel('Used')
                    ->falseLabel('Never used')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->whereNotNull('last_used_at'),
                        false: fn (Builder $query): Builder => $query->whereNull('last_used_at'),
                        blank: fn (Builder $query): Builder => $query,
                    ),
                Filter::make('expired')
                    ->label('Expired')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('expires_at')->where('expires_at', '<', now())),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make()
                    ->label('Revoke'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Revoke selected'),
                ]),
            ]);
    }
}
