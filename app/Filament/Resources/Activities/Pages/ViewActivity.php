<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Pages;

use App\Filament\Resources\Activities\ActivityResource;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

final class ViewActivity extends ViewRecord
{
    protected static string $resource = ActivityResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('created_at')
                ->label('When')
                ->dateTime('Y-m-d H:i:s'),
            TextEntry::make('log_name')->label('Log')->badge()->placeholder('default'),
            TextEntry::make('description')->label('Event')->badge(),
            TextEntry::make('subject_type')
                ->label('Subject type')
                ->placeholder('—'),
            TextEntry::make('subject_id')
                ->label('Subject id')
                ->placeholder('—'),
            TextEntry::make('causer_type')
                ->label('Causer type')
                ->placeholder('—'),
            TextEntry::make('causer.name')
                ->label('Causer')
                ->placeholder('system'),
            TextEntry::make('event')
                ->label('Event name')
                ->placeholder('—'),
            TextEntry::make('batch_uuid')
                ->label('Batch UUID')
                ->placeholder('—'),
            KeyValueEntry::make('properties')
                ->label('Properties')
                ->columnSpanFull(),
        ]);
    }
}
