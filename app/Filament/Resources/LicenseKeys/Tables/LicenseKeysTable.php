<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeys\Tables;

use App\Enums\LicenseKeyStatus;
use App\Models\LicenseKey;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class LicenseKeysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->copyable()
                    ->limit(40),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('type.name')
                    ->label('Type')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('customer.email')
                    ->label('Customer')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('validity_amount')
                    ->label('Validity')
                    ->formatStateUsing(function (mixed $state, LicenseKey $record): string {
                        if ($record->validity_unit->isLifetime()) {
                            return 'Lifetime';
                        }

                        if (! is_numeric($state)) {
                            return '—';
                        }

                        return $state.' '.$record->validity_unit->value;
                    })
                    ->sortable(),
                IconColumn::make('requires_hwid_check')
                    ->label('HWID')
                    ->boolean(),
                TextColumn::make('activated_at')
                    ->label('Activated')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('check_count')
                    ->label('Checks')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_checked_at')
                    ->label('Last check')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('team.name')
                    ->label('Team')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(LicenseKeyStatus::class),
                SelectFilter::make('product')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('type')
                    ->label('Type')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
