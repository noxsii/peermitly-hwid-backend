# Admin-Menü: Links zu API-Dokumentation und Horizon

## Datum

2026-07-06

## Bereich

Admin-Panel (Filament), Navigation

## Kurzbeschreibung

Im Admin-Menü gibt es ganz unten eine neue Gruppe „System" mit zwei externen Links: API-Dokumentation und Horizon.

## Was ist neu?

- Neuer Menüpunkt „API-Dokumentation" — öffnet die Scramble-API-Dokumentation (`/docs/api`) in einem neuen Tab.
- Neuer Menüpunkt „Horizon" — öffnet das Laravel-Horizon-Dashboard (`/horizon`) in einem neuen Tab.

## Warum wurde das geändert?

Schnellzugriff für Administratoren auf API-Referenz und Queue-Monitoring, ohne URLs manuell eintippen zu müssen.

## Wie funktioniert es?

1. Im Admin-Panel anmelden.
2. In der Seitenleiste ganz nach unten scrollen zur Gruppe „System".
3. „API-Dokumentation" oder „Horizon" anklicken — öffnet sich jeweils in einem neuen Browser-Tab.

## Betroffene Bereiche

- Admin-Panel Seitenleiste (alle Seiten)

## Wichtige Hinweise

- Beide Ziele haben eigene Zugriffsbeschränkungen; der Menülink umgeht keine Berechtigungen.
- Die Links zeigen immer auf die aktuelle Domain der Anwendung.

## Technische Notizen

- `app/Providers/Filament/AdminPanelProvider.php`: `navigationItems()` mit zwei `NavigationItem`-Einträgen in der Gruppe „System"; Gruppenreihenfolge via `navigationGroups(['Access', 'Content', 'App', 'System'])` fixiert.
