# Login Seite — Scaffolding Spec

**Datum:** 2026-05-19
**Status:** Approved
**Scope:** Erste Inertia-v3-Vue-Page für `/login` mit Platzhalter-Inhalt. Reines Scaffolding, keine Auth-Logik.

## Ziel

Login-Route lädt im Browser, Inertia rendert eine Vue-Page, sichtbarer Test-Text bestätigt die Toolchain (Laravel → Inertia → Vite → Vue 3 → Tailwind v4). Grundlage für nachfolgende Component-Specs.

## Architektur

- **Route** (`routes/web.php`): `GET /login` → Inertia-Response `Auth/Login`, benannt `login`.
- **Page** (`resources/js/Pages/Auth/Login.vue`): Vue 3 `<script setup lang="ts">`, `<Head title="Login" />`, zentrierter Container mit Heading + Test-Text. Tailwind-Klassen.
- **Kein Controller, keine Action**: Closure in `web.php` reicht für Platzhalter. `LoginAction` (DDD) kommt in späterer Spec, sobald Form/Submit dazukommt.
- **Kein Layout-Component**: Persistente Layouts (`useLayoutProps`, `<Layout>`) bewusst aufgeschoben — kommen mit echtem Component-Aufbau.

## Begleitende Fixes

- **`resources/views/app.blade.php`**: `@vite('resources/js/app.ts')` → `@vite(['resources/css/app.css', 'resources/js/app.ts'])`. Sonst lädt Tailwind nicht.

## Inertia-v3-Konfiguration (bereits vorhanden)

- `@inertiajs/vite` Plugin in `vite.config.js` → automatische Page-Resolution aus `resources/js/Pages/**`.
- `resources/js/app.ts` ist `createInertiaApp()` ohne Argumente — Plugin handled `resolve`/`setup`. Kein Eingriff nötig.

## Tests

- **`tests/Feature/Auth/LoginPageTest.php`** (Pest):
  - `GET /login` → 200 OK
  - Inertia-Component-Name = `Auth/Login`
  - Route-Name `login` löst auf `/login` auf

## Dokumentation

- **`docs/help/2026_05_19-login-seite-platzhalter.md`** per CLAUDE.md-Doku-Regel (benutzerorientierte Markdown-Datei).

## Out of Scope (eigene Specs später)

- Login-Form (`useForm`, `<Form>`, Validierung)
- `LoginAction` (DDD, `app/Actions/Auth/`)
- `POST /login`, Logout, Session-Handling
- Layout-Component + Header/Sidebar
- Register, Password-Reset, Email-Verify
- Team/Tenancy-Auswahl beim Login

## Annahmen

- Login-Page hängt nicht von authentifiziertem User ab → kein Middleware-Schutz.
- Vorhandener User-Model + Migration `0001_01_01_000000_create_users_table.php` bleibt unverändert.
- Pest `RefreshDatabase` ist global aktiv (siehe `tests/Pest.php`) — Feature-Test funktioniert ohne DB-Setup.

## Verifikation

1. `php artisan route:list --name=login` zeigt Route.
2. `bun run build` läuft ohne Fehler.
3. `php artisan test --compact --filter=LoginPageTest` grün.
4. Manueller Browser-Check via Herd-URL: Login-Page rendert mit Test-Text und Tailwind-Styling.