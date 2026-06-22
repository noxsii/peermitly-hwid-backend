<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeys;

use App\Filament\Resources\LicenseKeys\Pages\CreateLicenseKey;
use App\Filament\Resources\LicenseKeys\Pages\EditLicenseKey;
use App\Filament\Resources\LicenseKeys\Pages\ListLicenseKeys;
use App\Filament\Resources\LicenseKeys\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\LicenseKeys\Schemas\LicenseKeyForm;
use App\Filament\Resources\LicenseKeys\Tables\LicenseKeysTable;
use App\Models\LicenseKey;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class LicenseKeyResource extends Resource
{
    protected static ?string $model = LicenseKey::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|UnitEnum|null $navigationGroup = 'Licenses';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'key';

    public static function getNavigationLabel(): string
    {
        return 'License Keys';
    }

    public static function getModelLabel(): string
    {
        return 'License Key';
    }

    public static function getPluralModelLabel(): string
    {
        return 'License Keys';
    }

    public static function form(Schema $schema): Schema
    {
        return LicenseKeyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LicenseKeysTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLicenseKeys::route('/'),
            'create' => CreateLicenseKey::route('/create'),
            'edit' => EditLicenseKey::route('/{record}/edit'),
        ];
    }
}
