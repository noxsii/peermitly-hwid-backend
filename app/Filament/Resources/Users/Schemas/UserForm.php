<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

final class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                DateTimePicker::make('email_verified_at')
                    ->label('Email verified at'),
                Select::make('role')
                    ->label('Role')
                    ->options(UserRole::class)
                    ->default(UserRole::USER->value)
                    ->required(),
                TextInput::make('security_code')
                    ->label('Security code')
                    ->helperText('Used to verify the user when they open a support ticket. Auto-generated on creation.')
                    ->maxLength(4)
                    ->dehydrated(fn (?string $state): bool => filled($state)),
                TextInput::make('hwid')
                    ->label('Gerät (HWID)')
                    ->helperText('Hardware-ID, an die das Konto gebunden ist. Feld leeren, um die Bindung aufzuheben und Anmeldung von einem neuen Gerät zu erlauben.')
                    ->maxLength(255)
                    ->dehydrated(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->helperText('Inactive users are blocked from every authenticated request.')
                    ->default(true),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8),
            ]);
    }
}
