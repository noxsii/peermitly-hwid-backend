# Veraltete API-Tokens automatisch löschen

## Datum

2026-06-23

## Bereich

API-Tokens, Sicherheit, Wartung

## Kurzbeschreibung

Es gibt einen neuen automatischen Ablauf, der nicht mehr genutzte API-Tokens entfernt. Tokens, die noch nie verwendet wurden oder deren letzte Nutzung länger als 3 Tage zurückliegt, werden gelöscht.

## Was ist neu?

- Neuer Befehl `tokens:prune`, der veraltete API-Tokens aufräumt.
- Für jedes betroffene Token wird ein eigener Hintergrund-Job (`DeleteStaleTokenJob`) gestartet, der das Token löscht.
- Der Befehl läuft automatisch jede Nacht um 00:01 Uhr.

## Warum wurde das geändert?

Ungenutzte Tokens stellen ein Sicherheitsrisiko dar und sammeln sich mit der Zeit an. Durch das automatische Löschen bleiben nur aktiv genutzte Tokens bestehen.

## Wie funktioniert es?

1. Jede Nacht um 00:01 Uhr startet der Befehl `tokens:prune`.
2. Der Befehl sucht alle Tokens, deren letzte Nutzung (`last_used_at`) leer ist oder mehr als 3 Tage zurückliegt.
3. Für jedes gefundene Token wird ein Job in die Warteschlange gelegt.
4. Der Job löscht das Token endgültig.

Manuell auslösbar über:

```
php artisan tokens:prune
```

## Betroffene Bereiche

- API-Token-Verwaltung (Einstellungen)
- Geplante Aufgaben (Scheduler)
- Warteschlange / Horizon

## Wichtige Hinweise

- Auch frisch erstellte, aber noch nie genutzte Tokens werden entfernt, wenn sie zum Ausführungszeitpunkt noch nicht verwendet wurden.
- Ein Token, dessen letzte Nutzung genau 3 Tage zurückliegt, wird ebenfalls gelöscht.
- Die Löschung ist endgültig und kann nicht rückgängig gemacht werden.

## Beispiel

Ein Benutzer erstellt am Montag ein Token und nutzt es nicht mehr. Am Donnerstagnacht (mehr als 3 Tage ohne Nutzung) wird das Token automatisch gelöscht und funktioniert danach nicht mehr.

## Technische Notizen

- Befehl: `App\Console\Commands\PruneStaleTokens` (`tokens:prune`)
- Job: `App\Jobs\DeleteStaleTokenJob`
- Action: `App\Actions\Tokens\DeleteStaleTokenAction`
- Zeitplan in `routes/console.php`: `->dailyAt('00:01')` (Cron `1 0 * * *`)
- Tests: `tests/Feature/Tokens/PruneStaleTokensCommandTest.php`