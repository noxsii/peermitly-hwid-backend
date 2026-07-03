# Nuxt-Anleitung hinzugefügt

## Datum

2026-07-03

## Bereich

Dokumentation, Sites

## Kurzbeschreibung

Es gibt jetzt eine eigene Hilfe-Seite für das Erstellen von **Nuxt**-Sites.

## Was ist neu?

- Neue Doku-Seite **Nuxt** unter `/guide/nuxt`.
- Sie erscheint im Menü unter **Sites**.
- Die Anleitung erklärt Module, Git, Port, HTTPS und das Starten des Dev-Servers.

## Warum wurde das geändert?

Nuxt wurde als neues Framework hinzugefügt und war noch nicht dokumentiert.

## Wie funktioniert es?

1. In den Docs im Bereich **Sites** den Punkt **Nuxt** öffnen.
2. Die Seite beschreibt: Voraussetzungen (Node, nginx, DNS), Module (ESLint, Nuxt UI, Content, Image, Fonts, Icon, Scripts, Test Utils), Git-Option, Port (Standard 3000), HTTPS und Start des Dev-Servers.

## Betroffene Bereiche

- Docs-Menü (Sidebar)
- Neue Seite `/guide/nuxt`

## Wichtige Hinweise

- Der Dev-Server muss laufen (`npm run dev`), damit `https://your-name.peer` die App anzeigt.
- Für Nuxt-Sites wird Node benötigt.
- Standard-Port ist 3000.

## Technische Notizen

- Inhalt: `resources/js/docs/nuxt.md`
- Navigation/gültiger Slug: `config/docs.php` (Abschnitt **Sites**)
- Test: `tests/Feature/DocsTest.php` prüft, dass `/guide/nuxt` rendert.
- Datenquelle: `noxHWIDSpoofer` → `src/lib/frameworks.ts`, `src-tauri/src/sites/scaffold.rs`.
