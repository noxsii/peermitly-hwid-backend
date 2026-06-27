# Backup-Funktion entfernt

## Datum

2026-06-27

## Bereich

Backups, API, Admin-Bereich (Filament)

## Kurzbeschreibung

Die komplette Backup-Funktion wurde aus der Anwendung entfernt. Das betrifft die Datenbank, den Admin-Bereich und die API.

## Was ist neu?

Die Backup-Funktion gibt es nicht mehr. Folgendes wurde entfernt:

- Die Datenbanktabelle `backups` inklusive aller gespeicherten Backups
- Der Admin-Bereich unter `/admin/backups` (Liste und Detailansicht)
- Die API-Endpunkte zum Abrufen und Anlegen von Backups

## Warum wurde das geändert?

Die Backup-Funktion wird nicht mehr benötigt und wurde vollständig zurückgebaut.

## Wie funktioniert es?

Es gibt keine Bedienung mehr. Aufrufe der bisherigen Backup-Seiten oder API-Endpunkte sind nicht mehr verfügbar.

## Betroffene Bereiche

- Admin-Bereich: Menüpunkt und Seiten für Backups
- API: `GET /api/backups` und `POST /api/backups`
- Datenbank: Tabelle `backups`
- Benutzer-Datensatz: Verknüpfung zu Backups

## Wichtige Hinweise

- Alle vorhandenen Backup-Daten werden beim Ausführen der Migration gelöscht und können nicht wiederhergestellt werden.
- Clients, die bisher Backups über die API hochgeladen haben, erhalten ab sofort einen Fehler (Endpunkt nicht gefunden).

## Technische Notizen

- Entfernte Migration legt die Tabelle `backups` per `dropIfExists` ab (`2026_06_27_120000_drop_backups_table`).
- Entfernt: Model `Backup`, Filament-Resource `Backups`, API-Controller/Request/Resource, Action `StoreBackupAction`, Factory und zugehörige Tests sowie die `backups()`-Relation im `User`-Model.