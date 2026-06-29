# Neue Docs-Seite (/guide)

## Datum

2026-06-29

## Bereich

Dokumentation, Außendarstellung, Frontend

## Kurzbeschreibung

Es gibt eine neue, öffentliche Dokumentations-Seite unter `/guide`. Sie ist im Stil der Landingpage gehalten (dunkel, Orange-Akzent) und enthält aktuell die Seite „Introduction".

## Was ist neu?

- Öffentliche Docs unter `/guide` und `/guide/{seite}`
- Linke Navigation mit Sektionen
- „On this page"-Inhaltsverzeichnis rechts, das beim Scrollen mitläuft
- Volltext-Suche per Klick oder Tastenkürzel `⌘K` / `Strg+K`
- Code-Blöcke mit Syntax-Hervorhebung und Kopier-Button
- Vor/Zurück-Navigation am Seitenende

## Warum wurde das geändert?

Nutzer brauchen eine verständliche Anleitung zum Produkt. Die Seite ist die Grundlage, auf der weitere Hilfe-Inhalte schrittweise ergänzt werden.

## Wie funktioniert es?

1. Seiten werden als Markdown-Dateien unter `resources/js/docs/` gepflegt.
2. Beim Build werden sie in fertiges HTML mit Code-Hervorhebung umgewandelt.
3. Die Navigation und gültigen Seiten stehen in `config/docs.php`.
4. Eine neue Seite anlegen: Markdown-Datei hinzufügen und einen Eintrag in `config/docs.php` ergänzen.

## Betroffene Bereiche

- Neue Route `/guide/{slug?}` (Name `guide.show`)
- Landing-Navigation: neuer Punkt „Docs"
- Frontend-Komponenten unter `resources/js/components/docs/`

## Wichtige Hinweise

- Aktuell ist nur die Seite „Introduction" enthalten. Weitere Seiten folgen.
- Unbekannte Adressen unter `/guide/...` liefern eine 404-Seite.
- Der Basis-Pfad ist bewusst `/guide` (nicht `/docs`), um Konflikte mit `docs/api` zu vermeiden.
- Nach dem Hinzufügen oder Ändern von Doc-Seiten muss der Vite-Dev-Server neu gestartet werden (`composer run dev` bzw. `npm run dev`), damit die Änderungen lokal sichtbar werden.

## Technische Notizen

- Build-Verarbeitung über das Vite-Plugin `vite-plugin-docs.mjs` (markdown-it, markdown-it-anchor, Shiki Dual-Theme, gray-matter).
- Slug-Auflösung in `App\Actions\Docs\GetDocSlugsAction`, Rendering über `DocsController`.
- Komponenten: `DocsLayout`, `DocsSidebar`, `DocsToc`, `DocsContent`, `DocsSearch`, `DocsPager`; Loader/Suchindex in `resources/js/lib/docs.ts`.
- Tests: `tests/Feature/DocsTest.php`, `tests/Browser/DocsSmokeTest.php`.
