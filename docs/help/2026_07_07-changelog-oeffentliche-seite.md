# Changelog als öffentliche Seite mit Infinite Scroll

## Datum

2026-07-07

## Bereich

Landing Page, Changelog, Dashboard

## Kurzbeschreibung

Die Changelog-Seite ist aus dem eingeloggten Dashboard-Bereich auf die öffentliche Website umgezogen. Sie ist jetzt ohne Login unter `/changelog` erreichbar und wird nur im Footer der Landing Page verlinkt. Die Einträge laden per Infinite Scroll nach (Cursor-Pagination).

## Was ist neu?

- **Öffentliche Changelog-Seite** unter `/changelog` im Design der Landing Page: Zeitstrahl-Layout mit Versions-Badge, Datum und formatiertem Inhalt pro Release.
- **Infinite Scroll**: Beim Scrollen ans Seitenende laden automatisch die nächsten 10 Einträge nach (basierend auf Laravel `cursorPaginate` + Inertia `InfiniteScroll`).
- **Footer-Link "Changelog"** auf der Landing Page — der einzige Einstiegspunkt.
- **Changelog aus dem Dashboard entfernt**: Der Sidebar-Eintrag im eingeloggten Bereich ist weg.
- Footer-Anker-Links (Features, Pricing, FAQ) funktionieren jetzt auch von Unterseiten aus (führen zurück zur Landing Page).

## Warum wurde das geändert?

Release-Notes sind für alle Interessenten relevant, nicht nur für eingeloggte Nutzer. Eine öffentliche Seite macht Releases sichtbar (auch für Suchmaschinen) und entlastet das Dashboard.

## Wie funktioniert es?

1. Auf der Landing Page ganz nach unten scrollen.
2. Im Footer auf **Changelog** klicken.
3. Die Seite zeigt alle veröffentlichten Releases, neueste zuerst.
4. Beim Scrollen ans Ende laden weitere Einträge automatisch nach; währenddessen erscheint ein Ladeindikator.

Veröffentlicht wird ein Eintrag weiterhin über das Admin-Panel — nur Einträge mit gesetztem Veröffentlichungsdatum erscheinen auf der Seite.

## Betroffene Bereiche

- Landing Page Footer (neuer Link)
- Neue öffentliche Seite `/changelog`
- Dashboard-Sidebar (Changelog-Eintrag entfernt)

## Wichtige Hinweise

- Unveröffentlichte Einträge (ohne `published_at`) bleiben unsichtbar.
- Die Seite ist die einzige Stelle mit Changelog — im Dashboard gibt es keinen Zugang mehr.
- Die URL `/changelog` und der Routenname `changelog.index` sind unverändert; bestehende Links funktionieren weiter, nur ohne Login-Pflicht.

## Beispiel

Ein Besucher ohne Account öffnet peermitly.de, klickt im Footer auf "Changelog" und sieht sofort, dass Version 0.2.0 die IDE-Integration gebracht hat — ohne sich einloggen zu müssen.

## Technische Notizen

- Controller: `Inertia::scroll()` mit `Changelog::query()->whereNotNull('published_at')->orderByDesc('published_at')->orderByDesc('id')->cursorPaginate(10)` — sekundäre Sortierung nach `id` für stabile Cursor bei gleichem Veröffentlichungsdatum.
- Frontend: `resources/js/pages/changelog/Index.vue` neu geschrieben, nutzt Inertias `<InfiniteScroll data="entries" only-next preserve-url>`.
- Route aus der `auth`-Gruppe in den öffentlichen Bereich verschoben (`routes/web.php`).
- Sidebar-Eintrag in `resources/js/layout/AppSidebar.vue` entfernt.
- Tests in `tests/Feature/Changelog/ChangelogPageTest.php` auf öffentlichen Zugriff und Cursor-Pagination umgestellt.
