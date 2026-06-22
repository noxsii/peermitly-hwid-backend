<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApiTokens\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Laravel\Sanctum\PersonalAccessToken;

final class ApiTokenInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Name'),
                TextEntry::make('tokenable.name')
                    ->label('Owner')
                    ->placeholder('—'),
                TextEntry::make('tokenable.email')
                    ->label('Owner email')
                    ->placeholder('—'),
                TextEntry::make('abilities')
                    ->label('Abilities')
                    ->badge()
                    ->placeholder('—'),
                IconEntry::make('used')
                    ->label('Has been used')
                    ->boolean()
                    ->getStateUsing(fn (PersonalAccessToken $record): bool => $record->last_used_at !== null),
                TextEntry::make('last_used_at')
                    ->label('Last used at')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('Never'),
                TextEntry::make('created_at')
                    ->label('Created at')
                    ->dateTime('M j, Y H:i'),
                TextEntry::make('expires_at')
                    ->label('Expires at')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('Never'),
            ]);
    }
}
