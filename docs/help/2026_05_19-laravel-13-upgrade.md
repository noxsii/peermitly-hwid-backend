# Laravel 13 Upgrade

## Datum

2026-05-19

## Bereich

Framework, System, Modelle

## Kurzbeschreibung

Die Anwendung wurde von Laravel 12.59 auf Laravel 13.9 aktualisiert. Außerdem wurde das `Team`-Model auf den neuen `#[Fillable]`-Attribut-Stil umgebaut, der erst ab Laravel 13 verfügbar ist.

## Was ist neu?

- `laravel/framework: ^13.0` (installiert: v13.9.0)
- Symfony 7.4 → 8.0 (transitiv)
- `#[Fillable('name', 'owner_id')]` Class-Attribut am `Team`-Model statt `$guarded = []`
- `TeamFactory::for()` umbenannt zu `TeamFactory::ownedBy()` (vermeidet Signaturkonflikt mit Eloquent `Factory::for()`)

## Warum wurde das geändert?

Laravel 13 bringt:
- `#[Fillable]` Attribut für deklarative Mass-Assignment-Steuerung (saubererer als statisches `$fillable`-Array)
- Renamed CSRF Middleware `VerifyCsrfToken` → `PreventRequestForgery` mit zusätzlichem `Sec-Fetch-Site` Header-Check
- Stärkere Cache-Unserialization-Sicherheit via `serializable_classes`
- Aufgeräumte Queue-Event-Properties (`exception` statt `exceptionOccurred`, `connectionName` statt `connection`)
- Modernere Symfony-Komponenten

Im Projektcode war nur ein Spot betroffen (`TeamFactory::for()` Konflikt). Alle anderen Breaking Changes berührten dieses Projekt nicht.

## Wie funktioniert es?

Composer-Update:
```bash
composer update laravel/framework --with-all-dependencies
```

`Team` jetzt mit Attribut:
```php
#[Fillable('name', 'owner_id')]
final class Team extends Model
{
    use HasFactory, HasUuids;
    // kein $fillable, kein $guarded mehr nötig
}
```

`TeamFactory` nutzt eigenen Helper:
```php
Team::factory()->ownedBy($owner)->create();
```

## Betroffene Bereiche

- `composer.json` (framework constraint)
- `composer.lock` (Symfony 7.4 → 8.0, Laravel 12 → 13, plus transitive)
- `app/Models/Team.php` (Fillable-Attribut statt $guarded)
- `database/factories/TeamFactory.php` (`for()` → `ownedBy()`)
- `tests/Unit/Models/TeamTest.php` (Aufruf an `ownedBy()`)
- `tests/Unit/Models/UserTest.php` (`current_team_id` zu `toArray()`-Erwartung am Ende ergänzt)

## Wichtige Hinweise

- **Keine DB-Operationen wurden durchgeführt** während des Upgrades. Bestehende Migrations bleiben unangetastet.
- Falls die neuen Team-Migrations noch nicht gelaufen sind: `php artisan migrate` (ohne `--force`, ohne `fresh`).
- `VerifyCsrfToken`-Alias existiert weiter als Deprecation-Bridge — neue Code-Stellen sollen `PreventRequestForgery` nutzen.
- `tests/Pest.php` ruft noch `Str::createRandomStringsNormally()` und `Str::createUuidsNormally()` — Laravel 13 resettet diese Factories automatisch zwischen Tests, die Calls sind redundant aber nicht störend.

## Beispiel

```php
// Vorher (Laravel 12)
final class Team extends Model
{
    protected $guarded = [];
}

// Nachher (Laravel 13)
#[Fillable('name', 'owner_id')]
final class Team extends Model
{
}
```

## Technische Notizen

- Tests: 23/23 grün nach Upgrade (UserRole 4, Team 8, Login 6, User 1, Welcome 1, andere 3)
- Build: 455ms ohne Fehler
- Pint: passed
- Boost-Guidelines wurden automatisch via `composer post-update-cmd` aktualisiert