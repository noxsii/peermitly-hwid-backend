# Login-Seite Platzhalter

## Datum

2026-05-19

## Bereich

Authentifizierung, Routing, Frontend

## Kurzbeschreibung

Eine erste Login-Seite wurde eingerichtet. Sie zeigt aktuell nur einen Platzhalter-Text und dient als Grundlage für die kommenden Login-Komponenten (Formular, Validierung, Submit).

## Was ist neu?

- Neue Route `/login` (benannt: `login`)
- Neuer Controller `LoginController` mit Methode `index`
- Neue Inertia-Vue-Seite unter `resources/js/Pages/Auth/Login.vue`
- Veröffentlichte Inertia-Konfiguration (`config/inertia.php`) — `pages.paths` zeigt jetzt auf `resources/js/Pages` (Großbuchstabe), passend zu unserer Projektkonvention
- CSS-Einbindung in `resources/views/app.blade.php` ergänzt, damit Tailwind v4 geladen wird
- Pest-Feature-Tests für die neue Route (`tests/Feature/Auth/LoginPageTest.php`)

## Warum wurde das geändert?

Die Anwendung benötigt einen Einstiegspunkt für die Anmeldung. Bevor das tatsächliche Login-Formular und die Auth-Logik gebaut werden, soll erstmal die Seite rendern und die komplette Toolchain (Laravel → Inertia v3 → Vue 3 → Tailwind v4) verifizieren.

## Wie funktioniert es?

1. Benutzer ruft `/login` im Browser auf
2. `LoginController@index` wird ausgeführt
3. Controller gibt eine Inertia-Response mit Komponentenname `Auth/Login` zurück
4. Inertia lädt `resources/js/Pages/Auth/Login.vue` und rendert die Vue-Komponente
5. Browser zeigt einen zentrierten Card-Bereich mit Überschrift „Login" und Platzhalter-Hinweis

## Betroffene Bereiche

- `routes/web.php`
- `app/Http/Controllers/LoginController.php` (neu)
- `resources/js/Pages/Auth/Login.vue` (neu)
- `resources/views/app.blade.php`
- `config/inertia.php` (veröffentlicht und angepasst)
- `phpunit.xml` (SSR im Test-Env deaktiviert)
- `tests/Feature/Auth/LoginPageTest.php` (neu)

## Wichtige Hinweise

- Die Seite enthält **noch kein Formular** und **keine Auth-Logik**. Sie dient ausschließlich als Scaffolding.
- SSR ist im Test-Environment deaktiviert (`INERTIA_SSR_ENABLED=false` in `phpunit.xml`), damit die `assertInertia`-Assertion korrekt funktioniert. In Entwicklung und Produktion bleibt SSR aktiv.
- Die Konvention für Inertia-Pages ist `resources/js/Pages` (Großbuchstabe), nicht `pages`.

## Beispiel

Aufruf der Seite über die Herd-URL: `https://permitly.test/login` → es erscheint eine weiße Karte mit der Überschrift „Login" und dem Hinweis „Platzhalter — Login-Seite lädt. Komponenten folgen."

## Technische Notizen

- Inertia v3 nutzt automatische Page-Resolution via `@inertiajs/vite`-Plugin; `resources/js/app.ts` bleibt minimal (`createInertiaApp()` ohne Argumente).
- Die nächsten Schritte (eigene Specs): Login-Formular mit `useForm`, `LoginAction` im DDD-Stil unter `app/Actions/Auth/`, `POST /login`, Logout, optional Layout-Komponente.