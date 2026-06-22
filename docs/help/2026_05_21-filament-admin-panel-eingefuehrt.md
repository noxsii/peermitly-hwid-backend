# Filament Admin Panel eingeführt

## Datum

2026-05-21

## Bereich

Verwaltung, Benutzer, Berechtigungen

## Kurzbeschreibung

Das Admin-Backend Filament (Version 5) wurde in das Projekt integriert. Der Zugriff auf das Panel ist ausschließlich für Benutzer mit der Rolle **Super-Admin** möglich.

## Was ist neu?

- Filament v5 ist installiert und unter `/admin` erreichbar.
- Eine Login-Seite ist unter `/admin/login` verfügbar.
- Nur Benutzer mit der Rolle `super_admin` dürfen sich einloggen und sehen das Panel-Dashboard.
- Benutzer mit Rolle `user` oder `admin` erhalten beim Zugriff ein **403 Forbidden**.
- Nicht angemeldete Besucher werden zur Login-Seite weitergeleitet.

## Warum wurde das geändert?

Für die Verwaltung von Stammdaten, Konfigurationen und sensible Aktionen wird ein eigenständiges Admin-Backend benötigt. Filament liefert dafür eine ausgereifte, erweiterbare Oberfläche.

## Wie funktioniert es?

1. Öffne im Browser den Pfad `/admin`.
2. Melde dich mit einem Benutzeraccount an, der die Rolle **Super-Admin** besitzt.
3. Nach erfolgreichem Login erscheint das Filament-Dashboard.
4. Benutzer ohne diese Rolle sehen eine 403-Fehlerseite.

## Betroffene Bereiche

- Neue Route-Gruppe `/admin/*`
- Benutzerrollen-Prüfung beim Panel-Zugriff
- Authentifizierungs-Middleware für das Panel

## Wichtige Hinweise

- Die Rollen-Prüfung erfolgt durch eine eigene Middleware (`EnsureSuperAdmin`) und ist nicht im User-Model verdrahtet. So bleibt das Model schlank.
- Bestehende Inertia-Routen (`/license-keys`, `/customers`, …) sind unverändert. Filament arbeitet parallel zum bestehenden Frontend.
- Im ersten Schritt enthält das Panel nur das Standard-Dashboard. Filament-Resources für Lizenzschlüssel, Kunden etc. folgen in späteren Schritten.

## Beispiel

Ein Benutzer mit `role = super_admin` öffnet `https://permitly.test/admin` und gelangt zum Filament-Dashboard.

Ein Benutzer mit `role = admin` erhält beim selben Aufruf einen 403-Fehler.

## Technische Notizen

- Paket: `filament/filament:^5.0` (installierte Version v5.6.4)
- Panel-Provider: `App\Providers\Filament\AdminPanelProvider`
- Zugriffs-Middleware: `App\Http\Middleware\EnsureSuperAdmin` (prüft `UserRole::SUPER_ADMIN`)
- Custom Authenticate: `App\Http\Middleware\FilamentAuthenticate` (ersetzt Filament-Default und überspringt den `FilamentUser`-Interface-Check, damit die Rollen-Prüfung sauber in der Middleware bleibt und das `User`-Model frei von Filament-Interfaces ist)
- Tests: `tests/Feature/FilamentAccessTest.php` (4 Tests: guest redirect, user 403, admin 403, super_admin 200)