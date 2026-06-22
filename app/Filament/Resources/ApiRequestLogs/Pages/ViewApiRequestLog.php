<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApiRequestLogs\Pages;

use App\Filament\Resources\ApiRequestLogs\ApiRequestLogResource;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

final class ViewApiRequestLog extends ViewRecord
{
    protected static string $resource = ApiRequestLogResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('created_at')
                ->label('Time')
                ->dateTime('Y-m-d H:i:s'),
            TextEntry::make('method')->label('Method')->badge(),
            TextEntry::make('path')->label('Path')->fontFamily('mono')->copyable(),
            TextEntry::make('route_name')->label('Route')->placeholder('—'),
            TextEntry::make('status')->label('Status')->badge(),
            TextEntry::make('duration_ms')->label('Duration (ms)'),
            TextEntry::make('user.email')->label('User')->placeholder('guest'),
            TextEntry::make('licenseKey.key')->label('License key')->placeholder('—')->fontFamily('mono'),
            TextEntry::make('ip')->label('IP')->fontFamily('mono'),
            TextEntry::make('user_agent')->label('User-Agent')->placeholder('—'),
            KeyValueEntry::make('request_payload')
                ->label('Request payload')
                ->columnSpanFull(),
        ]);
    }
}
