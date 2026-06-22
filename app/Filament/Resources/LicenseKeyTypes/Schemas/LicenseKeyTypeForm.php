<?php

declare(strict_types=1);

namespace App\Filament\Resources\LicenseKeyTypes\Schemas;

use App\Enums\LicenseKeyGeneratorType;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class LicenseKeyTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('team_id')
                    ->label('Team')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(120),
                Select::make('generator_type')
                    ->label('Generator Type')
                    ->options([
                        LicenseKeyGeneratorType::UUID->value => 'UUID',
                        LicenseKeyGeneratorType::RANDOM->value => 'Random Characters',
                        LicenseKeyGeneratorType::PATTERN->value => 'Pattern Based',
                    ])
                    ->default(LicenseKeyGeneratorType::RANDOM->value)
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                KeyValue::make('configuration')
                    ->label('Configuration')
                    ->keyLabel('Option')
                    ->valueLabel('Value')
                    ->helperText('Generator-specific options (e.g. length, prefix, pattern). Keys depend on the generator type.')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
