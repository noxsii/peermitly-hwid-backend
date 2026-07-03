# Statistik-Widgets im Admin-Dashboard

## Datum

2026-07-03

## Bereich

Admin (Filament), Dashboard

## Kurzbeschreibung

Das Filament-Admin-Dashboard zeigt jetzt oben drei Kennzahlen-Kacheln mit Mini-Verlaufsgrafik.

## Was ist neu?

- **Benutzer** — Gesamtzahl aller Nutzer.
- **Aktive Abonnements** — Anzahl aktiver Abos, mit Hinweis auf die Gesamtzahl.
- **API-Schlüssel** — Anzahl vergebener API-Tokens.

Jede Kachel hat ein Symbol, eine Farbe und eine kleine 7-Tage-Verlaufskurve. Die Beschreibung zeigt die Neuzugänge der letzten 7 Tage.

## Warum wurde das geändert?

Das Dashboard war leer. Die wichtigsten Zahlen sind jetzt auf einen Blick sichtbar.

## Betroffene Bereiche

- Admin-Dashboard (Startseite nach dem Login ins Filament-Panel)

## Wichtige Hinweise

- Die Kacheln erscheinen automatisch oben auf dem Dashboard.
- Die Verlaufskurve zeigt neue Einträge pro Tag über die letzten 7 Tage.

## Technische Notizen

- Widget: `app/Filament/Widgets/StatsOverview.php` (Standard Filament `StatsOverviewWidget`).
- Wird per `discoverWidgets` automatisch geladen.
- Test: `tests/Feature/Filament/StatsOverviewTest.php`.
