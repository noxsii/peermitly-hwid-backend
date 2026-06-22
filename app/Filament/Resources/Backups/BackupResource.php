<?php

declare(strict_types=1);

namespace App\Filament\Resources\Backups;

use App\Filament\Resources\Backups\Pages\ListBackups;
use App\Filament\Resources\Backups\Pages\ViewBackup;
use App\Filament\Resources\Backups\Schemas\BackupInfolist;
use App\Filament\Resources\Backups\Tables\BackupsTable;
use App\Models\Backup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class BackupResource extends Resource
{
    protected static ?string $model = Backup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|UnitEnum|null $navigationGroup = 'Backups';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'client_backup_id';

    public static function getNavigationLabel(): string
    {
        return 'Backups';
    }

    public static function getModelLabel(): string
    {
        return 'Backup';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Backups';
    }

    public static function infolist(Schema $schema): Schema
    {
        return BackupInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BackupsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBackups::route('/'),
            'view' => ViewBackup::route('/{record}'),
        ];
    }
}
