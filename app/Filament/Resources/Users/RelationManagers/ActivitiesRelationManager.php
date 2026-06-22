<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

final class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Activity Log';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Event')
                    ->badge()
                    ->color(fn (Activity $record): string => match ($record->description) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('causer.name')
                    ->label('Changed by')
                    ->placeholder('system'),
                TextColumn::make('properties')
                    ->label('Changes')
                    ->formatStateUsing(function (Activity $record): string {
                        $changes = $record->properties?->toArray() ?? [];
                        if ($changes === []) {
                            return '—';
                        }

                        $attributes = $changes['attributes'] ?? null;
                        $old = $changes['old'] ?? null;

                        if (! is_array($attributes)) {
                            return '—';
                        }

                        $parts = [];
                        foreach ($attributes as $key => $newValue) {
                            $previous = is_array($old) ? ($old[$key] ?? null) : null;
                            $parts[] = sprintf(
                                '%s: %s → %s',
                                (string) $key,
                                $this->format($previous),
                                $this->format($newValue),
                            );
                        }

                        return implode("\n", $parts);
                    })
                    ->wrap()
                    ->limit(140)
                    ->tooltip(function (Activity $record): ?string {
                        $changes = $record->properties?->toArray() ?? [];
                        if ($changes === []) {
                            return null;
                        }

                        return json_encode($changes, JSON_PRETTY_PRINT) ?: null;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    private function format(mixed $value): string
    {
        if ($value === null) {
            return '∅';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_array($value)) {
            return json_encode($value) ?: '[]';
        }
        if (is_scalar($value)) {
            return (string) $value;
        }

        return '—';
    }
}
