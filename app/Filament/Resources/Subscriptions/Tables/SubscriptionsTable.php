<?php

declare(strict_types=1);

namespace App\Filament\Resources\Subscriptions\Tables;

use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->description(fn (Subscription $record): string => $record->user->email)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->formatStateUsing(fn (SubscriptionPlan $state): string => $state->label())
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (SubscriptionStatus $state): string => $state->label())
                    ->color(fn (SubscriptionStatus $state): string => match ($state) {
                        SubscriptionStatus::ACTIVE => 'success',
                        SubscriptionStatus::EXPIRED => 'gray',
                        SubscriptionStatus::CANCELED => 'danger',
                    })
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Access')
                    ->boolean()
                    ->getStateUsing(fn (Subscription $record): bool => $record->status === SubscriptionStatus::ACTIVE
                        && $record->ends_at->isFuture()),
                TextColumn::make('starts_at')
                    ->label('Starts')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label('Ends')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('plan')
                    ->label('Plan')
                    ->options(fn (): array => self::enumOptions(SubscriptionPlan::cases())),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(fn (): array => self::enumOptions(SubscriptionStatus::cases())),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
