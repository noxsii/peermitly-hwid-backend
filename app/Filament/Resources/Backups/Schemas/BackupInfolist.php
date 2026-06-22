<?php

declare(strict_types=1);

namespace App\Filament\Resources\Backups\Schemas;

use App\Models\Backup;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class BackupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('user.email')
                    ->label('Email'),
                TextEntry::make('client_backup_id')
                    ->label('Backup ID'),
                TextEntry::make('label')
                    ->label('Label')
                    ->placeholder('—'),
                TextEntry::make('machine_guid')
                    ->label('Machine GUID')
                    ->placeholder('—')
                    ->copyable(),
                TextEntry::make('client_created_at')
                    ->label('Created on client')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('—'),
                TextEntry::make('created_at')
                    ->label('Received')
                    ->dateTime('M j, Y H:i'),
                TextEntry::make('data')
                    ->label('Backup data')
                    ->columnSpanFull()
                    ->formatStateUsing(fn (Backup $record): string => (string) json_encode($record->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                    ->fontFamily('mono'),
            ]);
    }
}
