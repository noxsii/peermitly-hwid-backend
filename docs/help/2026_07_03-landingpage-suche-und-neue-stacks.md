# Landingpage um Suche und neue Stacks erweitert

## Datum

2026-07-03

## Bereich

Landingpage, Marketing

## Kurzbeschreibung

Die Startseite zeigt jetzt die Suchmaschinen **Meilisearch** und **Typesense** sowie **Nuxt** als unterstützten Stack.

## Was ist neu?

- **Features:** neue Karte „Built-in search engines" (Meilisearch & Typesense). Die Karte „Databases & services" nennt jetzt auch MongoDB, Meilisearch und Typesense. „Scaffold new apps" nennt zusätzlich Nuxt.
- **Supported stacks:** Meilisearch, Typesense und Redis zur Framework-Leiste hinzugefügt.
- **FAQ:** neue Frage „Does Peermitly support search engines?"; die Framework- und Scaffolding-Antworten nennen jetzt auch Nuxt.

## Warum wurde das geändert?

Neue Funktionen (Meilisearch, Typesense, Nuxt) waren dokumentiert, aber auf der Startseite nicht sichtbar. So passt das Marketing zum tatsächlichen Funktionsumfang.

## Betroffene Bereiche

- `resources/js/components/landing/LandingFeatures.vue`
- `resources/js/components/landing/LandingStacks.vue`
- `resources/js/components/landing/LandingFaq.vue`

## Wichtige Hinweise

- Reine Frontend-Änderung: nach dem Deploy `npm run build` bzw. im Dev `npm run dev`.

## Technische Notizen

- Features-Grid jetzt 9 Karten (sauberes 3×3-Raster).
