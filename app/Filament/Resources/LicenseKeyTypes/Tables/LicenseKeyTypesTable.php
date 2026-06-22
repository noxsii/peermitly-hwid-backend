<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeyTypes\Tables;

use App\Enums\LicenseKeyGeneratorType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

final class LicenseKeyTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('licenseKeys'))
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('generator_type')
                    ->label('Generator')
                    ->badge()
                    ->sortable(),
                TextColumn::make('team.name')
                    ->label('Team')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('license_keys_count')
                    ->label('Keys')
                    ->sortable()
                    ->numeric(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                SelectFilter::make('generator_type')
                    ->label('Generator')
                    ->options([
                        LicenseKeyGeneratorType::UUID->value => 'UUID',
                        LicenseKeyGeneratorType::RANDOM->value => 'Random Characters',
                        LicenseKeyGeneratorType::PATTERN->value => 'Pattern Based',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Active'),
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
