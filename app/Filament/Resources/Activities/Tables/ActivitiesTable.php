<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

final class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
                TextColumn::make('log_name')
                    ->label('Log')
                    ->badge()
                    ->sortable()
                    ->placeholder('default'),
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
                TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(static function (Activity $record): string {
                        $type = is_string($record->subject_type)
                            ? class_basename($record->subject_type)
                            : '—';
                        $id = $record->subject_id;

                        return $id !== null ? "{$type} #{$id}" : $type;
                    })
                    ->searchable(),
                TextColumn::make('causer.name')
                    ->label('User')
                    ->placeholder('system')
                    ->searchable(),
                TextColumn::make('properties')
                    ->label('Changes')
                    ->formatStateUsing(static function (Activity $record): string {
                        $changes = $record->properties->toArray();
                        if ($changes === []) {
                            return '—';
                        }

                        $attributes = $changes['attributes'] ?? null;
                        $old = $changes['old'] ?? null;

                        if (! is_array($attributes)) {
                            return json_encode($changes) ?: '—';
                        }

                        $parts = [];
                        foreach ($attributes as $key => $newValue) {
                            $previous = is_array($old) ? ($old[$key] ?? null) : null;
                            $parts[] = sprintf(
                                '%s: %s → %s',
                                (string) $key,
                                self::format($previous),
                                self::format($newValue),
                            );
                        }

                        return implode("\n", $parts);
                    })
                    ->wrap()
                    ->limit(160)
                    ->tooltip(static function (Activity $record): ?string {
                        $changes = $record->properties->toArray();
                        if ($changes === []) {
                            return null;
                        }

                        return json_encode($changes, JSON_PRETTY_PRINT) ?: null;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Log')
                    ->options(static fn (): array => self::distinct('log_name')),
                SelectFilter::make('description')
                    ->label('Event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
                SelectFilter::make('subject_type')
                    ->label('Subject Type')
                    ->options(static fn (): array => self::distinct('subject_type', fn (string $v): string => class_basename($v))),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    /**
     * @param  callable(string): string|null  $labelMap
     * @return array<string, string>
     */
    private static function distinct(string $column, ?callable $labelMap = null): array
    {
        return Activity::query()
            ->whereNotNull($column)
            ->select($column)
            ->distinct()
            ->orderBy($column)
            ->pluck($column)
            ->filter(fn ($v): bool => is_string($v) && $v !== '')
            ->mapWithKeys(fn (string $v): array => [
                $v => $labelMap !== null ? $labelMap($v) : $v,
            ])
            ->all();
    }

    private static function format(mixed $value): string
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

        return (string) $value;
    }
}
