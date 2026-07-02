# Astro-Anleitung hinzugefügt

## Datum

2026-07-02

## Bereich

Dokumentation, Sites

## Kurzbeschreibung

Es gibt jetzt eine eigene Hilfe-Seite für das Erstellen von **Astro**-Sites, passend zu den Anleitungen für Vue und React.

## Was ist neu?

- Neue Doku-Seite **Astro** unter `/guide/astro`.
- Sie erscheint im Menü unter **Sites**, direkt nach **React**.
- Die Anleitung erklärt Templates, Integrationen, Port, HTTPS und das Starten des Dev-Servers.

## Warum wurde das geändert?

Astro kann in Peermitly als Site erstellt werden, es fehlte aber die Doku. Damit sind Vue, React und Astro vollständig beschrieben.

## Wie funktioniert es?

1. In den Docs im Bereich **Sites** den Punkt **Astro** öffnen.
2. Die Seite beschreibt: Voraussetzungen (Node, nginx, DNS), Template-Auswahl (Basics, Blog, Starlight, Minimal), Integrationen (React, Vue, Svelte, Solid, Tailwind, MDX, Sitemap, Partytown), Port (Standard 4321), HTTPS und Start des Dev-Servers.

## Betroffene Bereiche

- Docs-Menü (Sidebar)
- Neue Seite `/guide/astro`

## Wichtige Hinweise

- Der Dev-Server muss laufen (`npm run dev`), damit `https://your-name.peer` die App anzeigt.
- Für Astro-Sites wird Node benötigt.
- Standard-Port ist 4321 (nicht 5173 wie bei Vue/React).

## Technische Notizen

- Inhalt: `resources/js/docs/astro.md`
- Navigation/gültiger Slug: `config/docs.php` (Abschnitt **Sites**)
- Test: `tests/Feature/DocsTest.php` prüft, dass `/guide/astro` rendert.
- Datenquelle für Optionen: `noxHWIDSpoofer` → `src/lib/frameworks.ts` und `src-tauri/src/sites/scaffold.rs`.