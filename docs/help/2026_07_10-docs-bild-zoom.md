# Guide-Seiten: Bilder per Klick zoomen

## Datum

2026-07-10

## Bereich

Dokumentation (Guide-Seiten auf der Website)

## Kurzbeschreibung

Bilder in den Guide-Seiten (z. B. Screenshots) lassen sich jetzt per Klick vergrößern. Ein Klick öffnet das Bild in einem Vollbild-Overlay.

## Was ist neu?

- Jedes Bild in einem Guide zeigt beim Überfahren einen Zoom-Cursor.
- Ein Klick öffnet das Bild groß in einem abgedunkelten Overlay (mit Weichzeichner-Hintergrund).
- Schließen: Klick irgendwo, das X oben rechts oder die Escape-Taste.

## Warum wurde das geändert?

Screenshots in den Guides sind teils sehr breit (z. B. der Profiler-Screenshot) und im Textfluss schwer lesbar. Mit dem Zoom lassen sich Details bequem ansehen.

## Wie funktioniert es?

1. Guide-Seite öffnen, z. B. `https://peermitly.de/guide/php`.
2. Auf ein Bild klicken — es öffnet sich vergrößert im Overlay.
3. Mit Klick, X-Button oder `Esc` schließen.

## Betroffene Bereiche

- Alle Guide-Seiten unter `/guide/…` mit Bildern
- Komponente `resources/js/components/docs/DocsContent.vue`
- Styles in `resources/css/app.css` (`.docs-prose img`)

## Wichtige Hinweise

- Funktioniert automatisch für alle bestehenden und künftigen Bilder in Guides — nichts muss pro Bild konfiguriert werden.
- Beim Seitenwechsel schließt sich ein offenes Overlay automatisch.

## Technische Notizen

- Klick-Delegation auf dem gerenderten Markdown-Container (`v-html`), Overlay per `Teleport` an `body`, Fade-Transition, `role="dialog"` + `aria-modal` für Screenreader.
- Browser-Test "a guide image opens in a zoom overlay and closes again" in `tests/Browser/DocsSmokeTest.php`.