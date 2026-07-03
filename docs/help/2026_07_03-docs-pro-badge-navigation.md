# PRO-Kennzeichnung in der Docs-Navigation

## Datum

2026-07-03

## Bereich

Dokumentation, Navigation

## Kurzbeschreibung

Einträge in der Docs-Navigation können jetzt als **PRO** markiert werden. Als Erstes ist **Debug** ein Pro-Feature.

## Was ist neu?

- Neben markierten Menüpunkten erscheint ein kleines **PRO**-Badge.
- **Debug** ist als erstes Pro-Feature gekennzeichnet.

## Warum wurde das geändert?

Nutzer sollen auf einen Blick sehen, welche Features zum kostenpflichtigen Pro-Plan gehören.

## Wie funktioniert es?

- In `config/docs.php` bekommt ein Navigations-Eintrag zusätzlich `'pro' => true`.
- Die Docs-Sidebar zeigt dann automatisch das PRO-Badge neben dem Titel.

## Betroffene Bereiche

- Docs-Sidebar (`/guide/...`)

## Wichtige Hinweise

- Weitere Features lassen sich später genauso markieren: `'pro' => true` beim jeweiligen Eintrag.

## Technische Notizen

- `config/docs.php`: Feld `pro` (optional) pro Nav-Item.
- Type `DocNavItem.pro?: boolean`; Badge in `components/docs/DocsSidebar.vue`.
