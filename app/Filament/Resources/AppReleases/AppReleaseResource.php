<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppReleases;

use App\Filament\Resources\AppReleases\Pages\CreateAppRelease;
use App\Filament\Resources\AppReleases\Pages\EditAppRelease;
use App\Filament\Resources\AppReleases\Pages\ListAppReleases;
use App\Filament\Resources\AppReleases\Schemas\AppReleaseForm;
use App\Filament\Resources\AppReleases\Tables\AppReleasesTable;
use App\Models\AppRelease;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class AppReleaseResource extends Resource
{
    protected static ?string $model = AppRelease::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'App';

    protected static ?int $navigationSort = 30;

    public static function getNavigationLabel(): string
    {
        return 'App-Versionen';
    }

    public static function getModelLabel(): string
    {
        return 'App-Version';
    }

    public static function getPluralModelLabel(): string
    {
        return 'App-Versionen';
    }

    public static function form(Schema $schema): Schema
    {
        return AppReleaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AppReleasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAppReleases::route('/'),
            'create' => CreateAppRelease::route('/create'),
            'edit' => EditAppRelease::route('/{record}/edit'),
        ];
    }
}
