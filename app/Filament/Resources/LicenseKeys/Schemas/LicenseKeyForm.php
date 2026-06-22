<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeys\Schemas;

use App\Enums\LicenseKeyStatus;
use App\Enums\LicenseValidityUnit;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class LicenseKeyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status')
                    ->options(LicenseKeyStatus::class)
                    ->default(LicenseKeyStatus::PENDING->value)
                    ->required(),
                Select::make('team_id')
                    ->label('Team')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('license_key_type_id')
                    ->label('Type')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'email')
                    ->searchable()
                    ->preload(),
                TextInput::make('validity_amount')
                    ->label('Validity amount')
                    ->numeric()
                    ->minValue(1),
                Select::make('validity_unit')
                    ->label('Validity unit')
                    ->options(LicenseValidityUnit::class)
                    ->required(),
                TextInput::make('max_activations')
                    ->label('Max activations')
                    ->numeric()
                    ->minValue(1)
                    ->placeholder('Unlimited'),
                Toggle::make('requires_hwid_check')
                    ->label('Requires HWID'),
                DateTimePicker::make('activated_at')
                    ->label('Activated at'),
                DateTimePicker::make('expires_at')
                    ->label('Expires at'),
                DateTimePicker::make('last_checked_at')
                    ->label('Last checked at'),
                TextInput::make('check_count')
                    ->label('Check count')
                    ->numeric()
                    ->default(0)
                    ->required(),
                DateTimePicker::make('revoked_at')
                    ->label('Revoked at'),
                Textarea::make('revoked_reason')
                    ->label('Revoke reason')
                    ->columnSpanFull(),
            ]);
    }
}
