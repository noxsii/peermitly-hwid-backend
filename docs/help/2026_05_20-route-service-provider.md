# Eigene RouteServiceProvider und Route-Aufteilung

## Datum

2026-05-20

## Bereich

Routing, Architektur

## Kurzbeschreibung

Die Route-Definitionen sind nicht mehr alle in `routes/web.php` gesammelt,
sondern pro Domäne in eigene Dateien aufgeteilt. Ein neuer
`App\Providers\RouteServiceProvider` lädt die Zusatz-Dateien.

## Was ist neu?

- `app/Providers/RouteServiceProvider.php` lädt:
  - `routes/auth.php` (Login, Logout)
  - `routes/settings.php` (Settings, Passwort, API-Tokens) — mit Prefix
    `settings` und Namens-Prefix `settings.`
  - `routes/license-keys.php` (alle Lizenzkey-Routen) — mit Prefix
    `license-keys` und Namens-Prefix `license-keys.`
- `routes/web.php` und `routes/api.php` werden weiterhin direkt von
  `bootstrap/app.php` registriert (Laravel-Standard).

## Warum wurde das geändert?

`routes/web.php` wurde schnell unübersichtlich. Pro Domäne eine eigene
Datei macht es einfacher, neue Endpoints zu finden, zu reviewen und zu
pflegen.

## Wie funktioniert es?

`bootstrap/providers.php` registriert den neuen RouteServiceProvider. In
dessen `boot()` werden die domänenspezifischen Route-Dateien mit den
passenden Middleware-Gruppen, URL-Prefixen und Namens-Prefixen geladen.

## Betroffene Bereiche

- `bootstrap/app.php` (unverändert für web/api)
- `bootstrap/providers.php` (neu: RouteServiceProvider)
- `app/Providers/RouteServiceProvider.php` (neu)
- `routes/auth.php`, `routes/settings.php`, `routes/license-keys.php` (neu)
- `routes/web.php` (nur noch `/` und `/dashboard`)

## Wichtige Hinweise

- Routen-Namen bleiben gleich: `login`, `logout`, `dashboard`,
  `settings.edit`, `settings.password.update`, etc.
- Neue Routen-Namen: `license-keys.index`, `license-keys.store`,
  `license-keys.types.*`, `settings.api-tokens.*`.

## Technische Notizen

Lädt-Beispiel im Provider:

```php
Route::middleware('web')
    ->prefix('license-keys')
    ->name('license-keys.')
    ->group(base_path('routes/license-keys.php'));
```
