<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApiTokens;

use App\Filament\Resources\ApiTokens\Pages\ListApiTokens;
use App\Filament\Resources\ApiTokens\Pages\ViewApiToken;
use App\Filament\Resources\ApiTokens\Schemas\ApiTokenInfolist;
use App\Filament\Resources\ApiTokens\Tables\ApiTokensTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Laravel\Sanctum\PersonalAccessToken;
use UnitEnum;

final class ApiTokenResource extends Resource
{
    protected static ?string $model = PersonalAccessToken::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|UnitEnum|null $navigationGroup = 'Access';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return 'API Tokens';
    }

    public static function getModelLabel(): string
    {
        return 'API Token';
    }

    public static function getPluralModelLabel(): string
    {
        return 'API Tokens';
    }

    public static function table(Table $table): Table
    {
        return ApiTokensTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApiTokenInfolist::configure($schema);
    }

    public static function canCreate(): bool
    {
        // Tokens are issued through the API, never created by hand here.
        return false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApiTokens::route('/'),
            'view' => ViewApiToken::route('/{record}'),
        ];
    }
}
