<laravel-boost-guidelines>
=== .ai/docs rules ===

# Docs für das Projekt

## Ziel

Nach jeder größeren Änderung, jedem neuen Feature, jeder relevanten Anpassung am Verhalten oder jeder Änderung an bestehenden Abläufen muss automatisch eine neue Dokumentationsdatei erstellt werden.

Diese Dokumentation dient später als Grundlage für die Hilfe-Seite, interne Nachvollziehbarkeit und Änderungsverfolgung.

---

## Pflicht: Dokumentation nach Änderungen

1. Nach jeder größeren Änderung oder jedem neuen Feature muss im Ordner `docs/help/` eine neue Markdown-Datei erstellt werden.
2. Die Datei muss alle wichtigen Informationen zu der Änderung enthalten.
3. Es darf nicht einfach bestehender Text überschrieben werden. Für jede größere Änderung ist eine eigene neue Datei anzulegen.
4. Kleine interne Refactorings ohne sichtbare Auswirkung müssen nicht dokumentiert werden, außer sie verändern Abläufe, Logik oder Bedienung.

---

## Dateiname

Der Dateiname muss immer mit dem aktuellen Datum beginnen.

**Format:**

`docs/help/2026_MM_DD-kurze-beschreibung.md`

**Beispiele:**

- `docs/help/2026_04_10-neue-kundenverwaltung.md`
- `docs/help/2026_04_10-rechnung-storno-hinzugefuegt.md`
- `docs/help/2026_04_11-tourenplanung-optimiert.md`

**Wichtig:**

- Immer `2026_MM_DD` verwenden
- Danach eine kurze, klare Beschreibung in Kleinbuchstaben
- Wörter mit Bindestrichen trennen
- Keine Umlaute im Dateinamen
- Keine Leerzeichen verwenden

---

## Wann eine Doku-Datei erstellt werden muss

Erstelle immer eine Datei, wenn mindestens einer dieser Punkte zutrifft:

- neues Feature
- sichtbare UI-Änderung
- neuer Ablauf
- bestehender Ablauf wurde verändert
- neue Einstellung oder Konfiguration
- neue Rolle, Berechtigung oder Einschränkung
- neue Schnittstelle oder Integration
- Validierung oder Verhalten wurde angepasst
- Datenstruktur wurde erweitert, wenn das für Benutzer oder Support relevant ist
- Fehler wurde behoben, der Auswirkungen auf die Nutzung hatte

---

## Inhalt der Datei

Jede Datei in `docs/help/` muss klar und verständlich aufgebaut sein und mindestens diese Bereiche enthalten:

# Titel der Änderung

## Datum

2026-04-10

## Bereich

Zum Beispiel: Kunden, Rechnungen, Tourenplanung, Benutzer, Einstellungen

## Kurzbeschreibung

Kurze Erklärung, was geändert wurde.

## Was ist neu?

Beschreibung der neuen Funktion oder Änderung.

## Warum wurde das geändert?

Kurze Begründung oder Problemstellung.

## Wie funktioniert es?

Schritt-für-Schritt-Erklärung der neuen oder geänderten Funktion.

## Betroffene Bereiche

Liste der betroffenen Seiten, Masken, Prozesse oder Module.

## Wichtige Hinweise

Besonderheiten, Einschränkungen oder Dinge, die beachtet werden müssen.

## Beispiel

Wenn sinnvoll, ein kurzes Praxisbeispiel.

## Technische Notizen

Optional: technische Details, nur wenn sie für spätere Pflege oder Support relevant sind.

---

## Schreibstil

Die Dokumentation muss:

- klar
- einfach verständlich
- konkret
- hilfreich für spätere Hilfe-Texte
- ohne unnötigen Entwicklerjargon

geschrieben sein.

Schreibe so, dass der Text später leicht für eine Hilfe-Seite, FAQ oder Benutzeranleitung wiederverwendet werden kann.

---

## Wichtige Regeln

- Keine Änderung ohne passende Datei in `docs/help/`
- Lieber eine kurze brauchbare Datei als gar keine
- Die Datei muss den tatsächlichen Stand der Änderung beschreiben
- Keine Platzhaltertexte wie `TODO`
- Keine rein technischen Commit-Beschreibungen
- Immer benutzerorientiert dokumentieren, nicht nur aus Entwicklersicht

---

## Verhalten für Claude

Wenn du eine größere Änderung oder ein neues Feature umsetzt, musst du am Ende zusätzlich eine neue Datei in `docs/help/` anlegen.

Du sollst dabei:

1. das aktuelle Datum verwenden
2. einen sinnvollen Dateinamen erzeugen
3. die Änderung sauber und verständlich dokumentieren
4. so schreiben, dass der Inhalt später für die Hilfe-Seite genutzt werden kann

Diese Dokumentation ist verpflichtender Teil jeder größeren Änderung.

=== .ai/laravel rules ===

# Laravel DDD & Architecture Guidelines

## Domain Structure (DDD)

- **Strict Layering:**
    - **Controllers:** Http entry point only. Map request to DTO, call Action, return Resource.
    - **Actions:** The ONLY place for business logic. Single responsibility (e.g., `CreateOrderAction`).
    - **Models:** Thin Active Records. No business logic.
    - **DTOs:** Strict data transport between layers.

## Enums

- **Enums:** Use native PHP 8.1+ Backed Enums.
- **Migrations:** NEVER use `$table->enum()`.
- **Casting:** Explicitly cast string columns to Enums in the Model's 
- **Validation:** Use `in:` rule with Enum cases for request validation.
- **Tests:** Use `->assertEnum()` for request validation.
- **Cases** only in UpperCase

## Multi-Tenancy (Team-based)

- **Tenant:** The `Team` model is the central tenant.
- **Migrations:** New domain tables MUST have: `$table->foreignId('team_id')();`
- **Creation:** Actions creating data MUST explicitly set the `team_id`.

## The "Thin Model" Rule

- **No Logic:** Models must not contain calculations or side effects.
- **No Scopes:** Do not write local scopes in Models.
- **Custom Builders:** Move all Eloquent query logic to dedicated `Builder` classes.
    - *Usage:* `$order->newQuery()->whereActive()->get()`

## Static Analysis & Types (PHPStan)

- **Strict Types:** `declare(strict_types=1);` is mandatory in all files.
- **Generics:** MUST use DocBlock Generics for Collections/Arrays.
    - *Example:* `/** @return Collection<int, Order> */`
- **Level 9:** Code must be compatible with PHPStan Level 9 (no `mixed`, explicit types).

## Enums & Database

- **PHP Enums:** Use native PHP 8.1+ Backed Enums.
- **Migrations:** NEVER use `$table->enum()`. Use `$table->string()` and cast it in the Model.
- **Casting:** Explicitly cast string columns to Enums in the Model's `$casts`.

## Modern PHP 8.4 Rules

- **Property Hooks:** Use Property Hooks to define computed properties in DTOs and Value Objects.
    - *Do:* `public string $fullName { get => $this->first . ' ' . $this->last; }`
    - *Don't:* Write `getFullName()` methods.
- **Asymmetric Visibility:** Use `public private(set)` for properties that should be readable publicly but mutable only internally (if not using `readonly` classes).
- **Array Functions:** Use native PHP 8.4 functions like `array_find()` and `array_any()` instead of loops or heavy Collection wrappers for simple array operations.
- **Syntax:** Use `match` over `switch`. Use `?->` for null safety.

## Action Pattern

- **Method:** Use `handle` or `execute`.
- **Composition:** Actions can inject other Actions.
- **No Repositories:** Use Eloquent Builders inside Actions.
- **Actions** are located in app/Actions, and are the only place for business logic. They should be single-purpose (e.g., `CreateOrderAction`).

## Service Pattern

- **Method:** Use `call`.
- **Composition:** Services can inject other Actions they in **Services/Actions**.
- **No Repositories:** Use Eloquent Builders inside Services.

## Filament

- **Filament:** Use Filament for admin panel.
- **Filament Resources:** Use Filament Resources for admin panel.
- **Filament Actions:** Use Filament Actions for admin panel.
- **Filament Components:** Use Filament Components for admin panel.
- **Filament Hooks:** Use Filament Hooks for admin panel.
- **Language:** Use German Language for Filament naming conventions.
- **Testing:** Use Filament Testing for admin panel.

=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4
- filament/filament (FILAMENT) - v5
- inertiajs/inertia-laravel (INERTIA_LARAVEL) - v3
- laravel/framework (LARAVEL) - v13
- laravel/horizon (HORIZON) - v5
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- laravel/scout (SCOUT) - v11
- livewire/livewire (LIVEWIRE) - v4
- tightenco/ziggy (ZIGGY) - v2
- larastan/larastan (LARASTAN) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- rector/rector (RECTOR) - v2
- @inertiajs/vue3 (INERTIA_VUE) - v3
- vue (VUE) - v3
- prettier (PRETTIER) - v3
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Follow existing application Enum naming conventions.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd at `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs. Never run commands to serve the site. It is always available.
- Use the `herd` CLI to manage services, PHP versions, and sites (e.g. `herd sites`, `herd services:start <service>`, `herd php:list`). Run `herd list` to discover all available commands.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

# Inertia v3

- Use all Inertia features from v1, v2, and v3. Check the documentation before making changes to ensure the correct approach.
- New v3 features: standalone HTTP requests (`useHttp` hook), optimistic updates with automatic rollback, layout props (`useLayoutProps` hook), instant visits, simplified SSR via `@inertiajs/vite` plugin, custom exception handling for error pages.
- Carried over from v2: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.
- Axios has been removed. Use the built-in XHR client with interceptors, or install Axios separately if needed.
- `Inertia::lazy()` / `LazyProp` has been removed. Use `Inertia::optional()` instead.
- Prop types (`Inertia::optional()`, `Inertia::defer()`, `Inertia::merge()`) work inside nested arrays with dot-notation paths.
- SSR works automatically in Vite dev mode with `@inertiajs/vite` - no separate Node.js server needed during development.
- Event renames: `invalid` is now `httpException`, `exception` is now `networkError`.
- `router.cancel()` replaced by `router.cancelAll()`.
- The `future` configuration namespace has been removed - all v2 future options are now always enabled.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

=== inertia-vue/core rules ===

# Inertia + Vue

Vue components must have a single root element.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

</laravel-boost-guidelines>
