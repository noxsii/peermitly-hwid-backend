<?php

declare(strict_types=1);

namespace App\Filament\Resources\Subscriptions\Schemas;

use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

final class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->getOptionLabelFromRecordUsing(
                        fn (User $record): string => "{$record->name} ({$record->email})",
                    )
                    ->searchable(['name', 'email'])
                    ->preload()
                    ->required(),
                Select::make('plan')
                    ->label('Plan')
                    ->options(self::enumOptions(SubscriptionPlan::cases()))
                    ->required()
                    ->live(),
                Select::make('status')
                    ->label('Status')
                    ->options(self::enumOptions(SubscriptionStatus::cases()))
                    ->default(SubscriptionStatus::ACTIVE->value)
                    ->required(),
                DateTimePicker::make('starts_at')
                    ->label('Starts at')
                    ->seconds(false)
                    ->native(false)
                    ->default(now()),
                DateTimePicker::make('ends_at')
                    ->label('Ends at')
                    ->seconds(false)
                    ->native(false)
                    ->helperText('Leave empty to calculate automatically from the plan.'),
            ]);
    }

    /**
     * @param  array<int, SubscriptionPlan|SubscriptionStatus>  $cases
     * @return array<string, string>
     */
    private static function enumOptions(array $cases): array
    {
        $options = [];

        foreach ($cases as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
