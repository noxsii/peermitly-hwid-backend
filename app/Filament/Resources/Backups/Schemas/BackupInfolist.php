<?php

declare(strict_types=1);

namespace App\Filament\Resources\Backups\Schemas;

use App\Models\Backup;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

final class BackupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Overview')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')->label('User'),
                        TextEntry::make('user.email')->label('Email'),
                        TextEntry::make('client_backup_id')->label('Backup ID'),
                        TextEntry::make('label')->label('Label')->placeholder('—'),
                        TextEntry::make('machine_guid')->label('Machine GUID')->placeholder('—')->copyable(),
                        TextEntry::make('file_size')
                            ->label('Size')
                            ->state(fn (Backup $record): string => self::formatBytes(Arr::get($record->data, 'file_size_bytes'))),
                        TextEntry::make('client_created_at')->label('Created on client')->dateTime('M j, Y H:i')->placeholder('—'),
                        TextEntry::make('created_at')->label('Received')->dateTime('M j, Y H:i'),
                    ]),

                Section::make('Disks')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('disks')
                            ->label('')
                            ->state(fn (Backup $record): array => self::asList(Arr::get($record->data, 'snapshot.disks')))
                            ->columns(3)
                            ->schema([
                                TextEntry::make('index')->label('Index')->placeholder('—'),
                                TextEntry::make('model')->label('Model')->placeholder('—'),
                                TextEntry::make('serial_number')->label('Serial')->placeholder('—')->copyable(),
                            ]),
                    ]),

                Section::make('Windows restore point')
                    ->collapsible()
                    ->collapsed()
                    ->visible(fn (Backup $record): bool => filled(Arr::get($record->data, 'restore_point')))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('rp_sequence')
                            ->label('Sequence')
                            ->state(fn (Backup $record): mixed => Arr::get($record->data, 'restore_point.sequence'))
                            ->placeholder('—'),
                        IconEntry::make('rp_created')
                            ->label('Created')
                            ->boolean()
                            ->state(fn (Backup $record): bool => (bool) Arr::get($record->data, 'restore_point.created', false)),
                        TextEntry::make('rp_size')
                            ->label('Size')
                            ->state(fn (Backup $record): string => self::formatBytes(Arr::get($record->data, 'restore_point.size_bytes'))),
                        TextEntry::make('rp_message')
                            ->label('Message')
                            ->state(fn (Backup $record): mixed => Arr::get($record->data, 'restore_point.message'))
                            ->placeholder('—'),
                    ]),

                Section::make('Raw data')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextEntry::make('data')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->fontFamily('mono')
                            ->formatStateUsing(fn (Backup $record): string => (string) json_encode(
                                $record->data,
                                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                            )),
                    ]),
            ]);
    }

    /**
     * @return array<int, mixed>
     */
    private static function asList(mixed $value): array
    {
        return is_array($value) ? array_values($value) : [];
    }

    private static function formatBytes(mixed $bytes): string
    {
        if (! is_numeric($bytes) || (int) $bytes <= 0) {
            return '—';
        }

        $mb = (int) $bytes / 1_048_576;

        return number_format($mb, 1).' MB';
    }
}
