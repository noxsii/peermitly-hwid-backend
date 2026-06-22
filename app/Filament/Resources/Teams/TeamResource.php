<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams;

use App\Filament\Resources\Teams\Pages\CreateTeam;
use App\Filament\Resources\Teams\Pages\EditTeam;
use App\Filament\Resources\Teams\Pages\ListTeams;
use App\Filament\Resources\Teams\RelationManagers\CustomersRelationManager;
use App\Filament\Resources\Teams\RelationManagers\LicenseKeysRelationManager;
use App\Filament\Resources\Teams\RelationManagers\LicenseKeyTypesRelationManager;
use App\Filament\Resources\Teams\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Teams\RelationManagers\UsersRelationManager;
use App\Filament\Resources\Teams\Schemas\TeamForm;
use App\Filament\Resources\Teams\Tables\TeamsTable;
use App\Models\Team;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return 'Teams';
    }

    public static function getModelLabel(): string
    {
        return 'Team';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Teams';
    }

    public static function form(Schema $schema): Schema
    {
        return TeamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
            CustomersRelationManager::class,
            ProductsRelationManager::class,
            LicenseKeyTypesRelationManager::class,
            LicenseKeysRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeams::route('/'),
            'create' => CreateTeam::route('/create'),
            'edit' => EditTeam::route('/{record}/edit'),
        ];
    }
}
