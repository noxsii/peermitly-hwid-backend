<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\LicenseKey;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

final class RecentLicenseKeysTable extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent license keys')
            ->query(
                LicenseKey::query()
                    ->with(['product:id,name,slug', 'customer:id,email', 'team:id,name'])
                    ->latest()
                    ->limit(10),
            )
            ->columns([
                TextColumn::make('key')
                    ->label('Key')
                    ->fontFamily('mono')
                    ->limit(28)
                    ->copyable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('team.name')
                    ->label('Team'),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->placeholder('—'),
                TextColumn::make('customer.email')
                    ->label('Customer')
                    ->placeholder('—'),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('Y-m-d')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since(),
            ])
            ->paginated(false);
    }
}
