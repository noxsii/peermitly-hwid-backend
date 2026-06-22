<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApiRequestLogs;

use App\Filament\Resources\ApiRequestLogs\Pages\ListApiRequestLogs;
use App\Filament\Resources\ApiRequestLogs\Pages\ViewApiRequestLog;
use App\Filament\Resources\ApiRequestLogs\Tables\ApiRequestLogsTable;
use App\Models\ApiRequestLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class ApiRequestLogResource extends Resource
{
    protected static ?string $model = ApiRequestLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSignal;

    protected static string|UnitEnum|null $navigationGroup = 'API';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'path';

    public static function getNavigationLabel(): string
    {
        return 'Request Logs';
    }

    public static function getModelLabel(): string
    {
        return 'API Request Log';
    }

    public static function getPluralModelLabel(): string
    {
        return 'API Request Logs';
    }

    public static function table(Table $table): Table
    {
        return ApiRequestLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApiRequestLogs::route('/'),
            'view' => ViewApiRequestLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
