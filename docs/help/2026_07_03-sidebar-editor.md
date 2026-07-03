# Sidebar Editor (Pro)

## Datum

2026-07-03

## Bereich

Dokumentation, Dashboard, Personalisierung

## Kurzbeschreibung

Neue Doku-Seite für den **Sidebar Editor** — damit lässt sich die Seitenleiste frei gestalten. Der Editor ist ein **Pro**-Feature.

## Was ist neu?

- Neue Doku-Seite **Sidebar Editor** unter `/guide/sidebar-editor` (im Bereich **Tools**, als Pro markiert).
- Beschreibt beide Wege: schnelles Ein-/Ausblenden (Einstellungen → Sidebar) und den vollen Drag-and-Drop-Editor.

## Warum wurde das geändert?

Der Sidebar Editor war noch nicht dokumentiert. Nutzer sollen wissen, wie sie ihre Navigation anpassen.

## Wie funktioniert es?

1. **Einstellungen → Sidebar:** Checkliste zum Ein-/Ausblenden einzelner Einträge.
2. **Sidebar Editor:** Einträge zwischen Kategorien ziehen, Kategorien umbenennen/hinzufügen/löschen/sortieren, Einträge per Auge aus-/einblenden, „Unused elements" zum Zurückholen, „Reset to default", „Done". Änderungen werden automatisch gespeichert.

## Betroffene Bereiche

- Docs-Sidebar (`/guide/sidebar-editor`)

## Wichtige Hinweise

- **Pro-Feature.** Anpassen der Sidebar erfordert Pro.
- Kern-Einträge (Home, Sites, Subscription, Settings) bleiben immer sichtbar.
- Nichts geht verloren — ausgeblendete Einträge landen in „Unused elements".

## Technische Notizen

- Inhalt: `resources/js/docs/sidebar-editor.md`
- Navigation/gültiger Slug: `config/docs.php` (Abschnitt **Tools**, `pro => true`).
- Screenshots: `public/images/screenshots/sidebar_1.png`, `sidebar_2.png`.
- Datenquelle: `noxHWIDSpoofer` → `views/dashboard/SidebarEditor.vue`, `components/dashboard/NavVisibilitySettings.vue`, `services/navigation.ts`.
