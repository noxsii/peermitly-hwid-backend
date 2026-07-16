# News Section — Design

## Datum

2026-07-16

## Ziel

Admin kann News posten (Filament). Frontend zeigt News als Übersicht (Karten) mit
Bild, Titel, Description und als Detailseite mit vollem Rich-Text. Footer verlinkt
die News-Übersicht.

## Kontext

Das bestehende **Changelog**-Feature ist die Vorlage: gleiches Model-Pattern
(HasUuids, casts, Fillable), Filament-Resource in Gruppe „Content", Inertia-Page mit
`v-html` prose-Styling. News erweitert dieses Muster um `slug`, `description`, `image`
und eine eigene Detailseite.

## Datenmodell

Neue Tabelle `news`:

| Spalte         | Typ                       | Notiz                                         |
|----------------|---------------------------|-----------------------------------------------|
| `id`           | `id()`                    |                                               |
| `uuid`         | `uuid()->unique()`        | wie Changelog (HasUuids)                       |
| `slug`         | `string()->unique()`      | Route-Key, aus Titel generiert                |
| `title`        | `string()`                |                                               |
| `description`  | `text()`                  | Kurztext für Übersicht                         |
| `image_path`   | `string()->nullable()`    | Pfad auf public disk, optional                |
| `content`      | `longText()`              | Rich-Text (HTML)                              |
| `published_at` | `timestamp()->nullable()->index()` | leer = Entwurf, nicht im Frontend    |
| `timestamps`   |                           |                                               |

Migration: nur `create`, kein `enum()`. Kein `team_id` (globaler Content wie
Changelog).

## Model `App\Models\News`

- Mirror `Changelog`: `HasFactory`, `HasUuids`, `uniqueIds()` = `['uuid']`.
- `getRouteKeyName(): string` → `'slug'`.
- `#[Fillable('slug', 'title', 'description', 'image_path', 'content', 'published_at')]`.
- `casts()`: `published_at => 'datetime'`, Strings explizit.
- Vollständiger `@property` / `@method` PHPDoc-Block wie Changelog (PHPStan Level 9).
- Kein Business-Logic im Model (Thin Model).

## Slug-Generierung

- Slug wird beim Erstellen aus dem Titel generiert (`Str::slug`), Eindeutigkeit durch
  Suffix (`-2`, `-3`, …) falls Kollision.
- Im Filament-Form editierbar (Slug-Feld sichtbar, vorbefüllt via `->live()` aus Titel).
- Logik in einer Action `App\Actions\News\GenerateNewsSlugAction` (single purpose,
  wiederverwendbar in Form + Tests). Keine Logik im Model.

## Admin — Filament `NewsResource`

- Gruppe `Content`, Icon `Heroicon::OutlinedNewspaper`, `navigationSort` hinter Changelog.
- Labels englisch im Code (Filament-Naming laut Projekt: deutsch für Anzeige —
  bestehende Resources nutzen englische Labels, daher **konsistent englisch bleiben**).
- **Form** (`Schemas/NewsForm`):
  - `TextInput title` (required, max 255, `->live(onBlur: true)` → Slug-Vorschlag)
  - `TextInput slug` (required, unique ignore-record, max 255)
  - `Textarea description` (required, max 500, columnSpanFull)
  - `FileUpload image` → `disk('public')`, `directory('news')`, `image()`, optional,
    gemappt auf `image_path`
  - `DateTimePicker published_at` (nullable, seconds false, native false)
  - `RichEditor content` (required, columnSpanFull, gleiche `toolbarButtons` wie
    ChangelogForm)
- **Table** (`Tables/NewsTable`): ImageColumn (image_path, public disk), Titel
  (searchable, limit 60), Published-IconColumn, `published_at` dateTime (Placeholder
  „Draft"), `updated_at` since (toggleable). Default-Sort `published_at desc`.
  EditAction + DeleteBulkAction.
- Pages: List / Create / Edit (Standard).

## Backend — Controller + Resource

`App\Http\Controllers\NewsController`:

- `index()`: `Inertia::render('news/Index', ['entries' => Inertia::scroll(...)])` —
  `News::query()->whereNotNull('published_at')->latest('published_at')->orderByDesc('id')->cursorPaginate(12)`,
  gemappt via `NewsResource::collection`.
- `show(News $news)`: 404 wenn `published_at === null`. `Inertia::render('news/Show',
  ['article' => new NewsResource($news)])`. Model-Binding über `slug`.

`App\Http\Resources\NewsResource`:

- Liste **und** Detail identisch, aber `content` nur wenn geladen/gebraucht — einfach:
  immer `uuid, slug, title, description, image_url, published_at, content`.
  `image_url` = `image_path ? Storage::disk('public')->url(image_path) : null`.
  (YAGNI: kein separater List/Detail-Resource, Content-Feld ist billig.)

Routes (`routes/web.php`, öffentlich, neben Changelog):

```php
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
```

## Frontend — Inertia/Vue

`resources/js/pages/news/Index.vue`:

- Layout `""` (standalone, wie Changelog), Header mit Logo + Log-in.
- Hero-Header „What's new / News".
- Karten-**Grid** (`md:grid-cols-2 lg:grid-cols-3`, gap), jede Karte = `<Link href="/news/{slug}">`:
  - Bild oben (`aspect-video`, `object-cover`) oder Fallback-Gradient wenn `image_url` null.
  - Titel, Description (line-clamp-3), Datum.
- `InfiniteScroll` wie Changelog (`data="entries" only-next preserve-url`).
- Empty-State (Newspaper-Icon) wie Changelog.
- `LandingFooter` unten.

`resources/js/pages/news/Show.vue`:

- Layout `""`, gleicher Header.
- Zurück-Link zu `/news`.
- Hero-Bild (falls vorhanden, `max-h-[420px] object-cover rounded`), sonst kein Bild.
- Datum, Titel (groß), dann `v-html` Content mit **exakt demselben** prose-Class-Block
  wie Changelog Index.vue.
- **SEO-Meta** im `<Head>` (jedes Tag mit eindeutigem `head-key` gegen Duplikate):
  - `<title>` = `{article.title} — Peermitly`
  - `meta description` = article.description
  - `link rel="canonical"` = absolute URL der News (`news.show`)
  - Open Graph: `og:type=article`, `og:title`, `og:description`, `og:url` (canonical),
    `og:image` (article.image_url, nur wenn vorhanden), `og:site_name=Peermitly`
  - Twitter: `twitter:card` = `summary_large_image` (wenn Bild, sonst `summary`),
    `twitter:title`, `twitter:description`, `twitter:image` (wenn Bild)
  - `article:published_time` = article.published_at
  - Absolute URLs (canonical/og:url) über eine per Controller mitgegebene `url`-Prop
    (`route('news.show', $news, absolute: true)`) — kein Client-Rätselraten um Host.
- `LandingFooter`.

Der Controller (`show`) gibt zusätzlich `'url' => route('news.show', $news)` (absolut)
an die Page, damit canonical/og:url serverseitig korrekt sind. Für `Index.vue` genügt
`<Head title>` + `meta description` wie beim Changelog (Übersicht braucht keine
OG-Vollausstattung).

`resources/js/components/landing/LandingFooter.vue`:

- News-Link ergänzen (neben bestehendem Changelog-Link, gleiche Stelle/Stil).

`resources/js/types` (bestehende `Changelog`-Type-Datei): `News`-Interface
(`uuid, slug, title, description, image_url: string | null, content, published_at`).

## Bild-Handling

- Filament `FileUpload` speichert auf `public` disk unter `news/`, `image_path` in DB.
- `public` disk muss verlinkt sein (`php artisan storage:link` — im Plan als Check).
- Resource liefert absolute URL via `Storage::disk('public')->url()`.

## Tests (Pest, Feature)

- `NewsFactory` mit States: default (published), `unpublished()`, `withoutImage()`.
- `tests/Feature/NewsPageTest.php`:
  - `/news` zeigt nur veröffentlichte News, keine Drafts.
  - `/news/{slug}` einer veröffentlichten News → 200, enthält content.
  - `/news/{slug}` eines Drafts → 404.
  - unbekannter Slug → 404.
- Filament-Test (`tests/Feature/Filament/NewsResourceTest.php`):
  - Admin kann News erstellen (Form absenden, DB-Eintrag + Slug gesetzt).
  - Slug-Kollision → eindeutiger Slug.
- Alle Tests grün via `php artisan test --compact --filter=News`.

## YAGNI / bewusst weggelassen

- Kein separater List- vs. Detail-Resource (Content ist billig, ein Resource reicht).
- Keine Kategorien/Tags/Autoren — nicht angefragt.
- Kein Team-Scoping (globaler Content wie Changelog).
- Keine Bild-Varianten/Thumbnails — public disk + object-cover reicht.

## Doku

Nach Implementierung: `docs/help/2026_07_16-news-section.md` (benutzerorientiert,
Pflicht laut Projekt-Regeln).

## Betroffene Bereiche

Neu: Model, Migration, Factory, Filament-Resource (+ Schema/Table/Pages), Controller,
Http-Resource, Action (Slug), 2 Vue-Pages, Types. Geändert: `routes/web.php`,
`LandingFooter.vue`.