# Changelog-API: 5 Einträge pro Seite

## Datum

2026-07-10

## Bereich

API

## Kurzbeschreibung

Der Endpunkt `GET /api/changelogs` liefert jetzt 5 Einträge pro Seite statt 20.

## Was ist neu?

Die Cursor-Pagination des Changelog-Endpunkts wurde von 20 auf 5 Einträge pro Seite reduziert. Struktur der Antwort unverändert (`data`, `meta.next_cursor`, `meta.prev_cursor`, `meta.per_page`).

## Warum wurde das geändert?

Die App zeigt Changelogs in kleinen Häppchen an; kleinere Seiten laden schneller und passen besser zur Darstellung.

## Wie funktioniert es?

Wie bisher: `GET /api/changelogs` (Sanctum-Token mit `app:use`). Weitere Seiten über `?cursor=<next_cursor>` laden; `meta.per_page` meldet jetzt `5`.

## Betroffene Bereiche

- API-Endpunkt `GET /api/changelogs`

## Wichtige Hinweise

- Clients, die alle Einträge laden, müssen ggf. mehr Seiten abrufen — der Cursor-Mechanismus bleibt identisch.

## Technische Notizen

- `App\Http\Controllers\Api\ChangelogController::index()`: `cursorPaginate(5)`.
- Neuer Test "it paginates five entries per page" in `tests/Feature/Api/ChangelogListTest.php`.