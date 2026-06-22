# Lizenzschlüsselverwaltung im Filament Admin Panel

## Datum

2026-05-21

## Bereich

Verwaltung, Lizenzschlüssel

## Kurzbeschreibung

Im Filament Admin Panel gibt es jetzt einen eigenen Navigationsbereich **Licenses**. Erster Eintrag darin: **License Keys** mit Tabelle, Filter, Anlegen, Bearbeiten und Löschen. Produkte und Lizenztypen folgen später unter demselben Bereich.

## Was ist neu?

- Neuer Navigationsbereich **Licenses** in der linken Sidebar.
- Untermenüpunkt **License Keys** zeigt alle Lizenzschlüssel des Systems.
- Tabelle mit Spalten: Key, Status (Badge), Product, Type, Customer, Validity, HWID, Activated, Expires, Checks und optional Last check / UUID / Team / Created at.
- Filter nach Status, Product und Type.
- Sortierung pro Spalte, Volltextsuche über Key, Product, Type, Customer und Team.
- Create- und Edit-Formular mit allen relevanten Feldern (Key, Status, Team, Product, Type, Customer, Validity, Max activations, HWID, Activated/Expires/Last checked / Revoked, Revoke reason).
- Mehrfach-Löschen über Bulk-Action.

## Warum wurde das geändert?

Bisher gab es die Lizenzschlüssel-Verwaltung nur im Inertia-Frontend. Für Super-Admins ist ein eigener Bereich im Admin-Panel hilfreich, um neben Benutzer und Teams auch alle Lizenz-Daten zentral zu sehen und zu pflegen.

## Wie funktioniert es?

1. Als Super-Admin im Panel unter `/admin` einloggen.
2. In der Sidebar Bereich **Licenses → License Keys** öffnen.
3. Tabelle filtern (Status, Product, Type) und durchsuchen.
4. Per Stift-Symbol einzelnen Schlüssel bearbeiten.
5. **Neuer Eintrag** über den Button rechts oben.
6. Mehrfachauswahl + **Delete selected** zum Massenlöschen.

## Betroffene Bereiche

- Admin-Panel-Sidebar: neuer Bereich **Licenses**
- Routen unter `/admin/license-keys/*`
- Filament-Resources-Ordner: `app/Filament/Resources/LicenseKeys/`

## Wichtige Hinweise

- Nur Benutzer mit Rolle **Super-Admin** sehen und nutzen die Ressource.
- Die bestehende Inertia-Frontend-Verwaltung (`/license-keys`) bleibt unverändert. Beide Wege bestehen parallel.
- Spalten wie **UUID**, **Last check**, **Team** und **Created at** sind in der Tabelle standardmäßig ausgeblendet und können über das Spaltenmenü eingeblendet werden.
- Produkte und Lizenztypen folgen als eigene Filament-Ressourcen im selben Navigationsbereich **Licenses** in einem späteren Schritt.

## Beispiel

Ein Super-Admin sucht alle aktiven HWID-gebundenen Schlüssel eines bestimmten Produkts:

1. `/admin/license-keys` öffnen.
2. Filter **Status = Active** + **Product = ...** wählen.
3. Spalte **HWID** prüfen – Häkchen-Icon zeigt, ob HWID-Pflicht aktiv ist.
4. Per Klick auf den Key öffnet sich die Bearbeitung.

## Technische Notizen

- Ressourcen-Klasse: `App\Filament\Resources\LicenseKeys\LicenseKeyResource`
- Formular: `App\Filament\Resources\LicenseKeys\Schemas\LicenseKeyForm`
- Tabelle: `App\Filament\Resources\LicenseKeys\Tables\LicenseKeysTable`
- Navigationsgruppe gesetzt via `protected static string|UnitEnum|null $navigationGroup = 'Licenses';`
- Navigationssortierung 10, Heroicon `OutlinedKey`.
- Generiert via `php artisan make:filament-resource LicenseKey --generate`, danach Felder/Spalten manuell verschlankt, sortiert, mit Filtern versehen und auf englische Labels umgestellt.
- Tests: 2 zusätzliche Cases in `tests/Feature/FilamentAccessTest.php` (Super-Admin darf Liste sehen, regulärer User bekommt 403).