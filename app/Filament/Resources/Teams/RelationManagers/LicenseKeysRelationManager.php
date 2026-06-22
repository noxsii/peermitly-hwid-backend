<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class LicenseKeysRelationManager extends RelationManager
{
    protected static string $relationship = 'licenseKeys';

    protected static ?string $title = 'License Keys';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('key')
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
                    ->placeholder('—'),
                TextColumn::make('customer.email')
                    ->label('Customer')
                    ->searchable()
                    ->placeholder('—'),
                IconColumn::make('requires_hwid_check')
                    ->label('HWID')
                    ->boolean(),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('Y-m-d')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
