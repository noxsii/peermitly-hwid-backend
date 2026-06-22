<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeyTypes;

use App\Filament\Resources\LicenseKeyTypes\Pages\CreateLicenseKeyType;
use App\Filament\Resources\LicenseKeyTypes\Pages\EditLicenseKeyType;
use App\Filament\Resources\LicenseKeyTypes\Pages\ListLicenseKeyTypes;
use App\Filament\Resources\LicenseKeyTypes\Schemas\LicenseKeyTypeForm;
use App\Filament\Resources\LicenseKeyTypes\Tables\LicenseKeyTypesTable;
use App\Models\LicenseKeyType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class LicenseKeyTypeResource extends Resource
{
    protected static ?string $model = LicenseKeyType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Licenses';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return 'Key Types';
    }

    public static function getModelLabel(): string
    {
        return 'Key Type';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Key Types';
    }

    public static function form(Schema $schema): Schema
    {
        return LicenseKeyTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LicenseKeyTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLicenseKeyTypes::route('/'),
            'create' => CreateLicenseKeyType::route('/create'),
            'edit' => EditLicenseKeyType::route('/{record}/edit'),
        ];
    }
}
