# Typesense-Anleitung hinzugefügt

## Datum

2026-07-03

## Bereich

Dokumentation, Search

## Kurzbeschreibung

Es gibt jetzt eine eigene Hilfe-Seite für **Typesense** im Bereich **Search**.

## Was ist neu?

- Neue Doku-Seite **Typesense** unter `/guide/typesense`.
- Erscheint im Menü unter **Search**, nach Meilisearch.
- Beschreibt Installation, Start/Stop/Restart, Port, API-Key, Config, Logs, Ressourcen und Scout-Anbindung.

## Warum wurde das geändert?

Typesense war als Such-System vorhanden, aber noch nicht dokumentiert.

## Wie funktioniert es?

1. Im Bereich **Search** den Punkt **Typesense** öffnen.
2. Eine der drei aktuellsten Versionen installieren, Dienst starten.
3. `.env`-Werte (inkl. `TYPESENSE_API_KEY=xyz`) in die App übernehmen.

## Betroffene Bereiche

- Docs-Menü (Sidebar), Bereich **Search**
- Neue Seite `/guide/typesense`

## Wichtige Hinweise

- Nur eine Version gleichzeitig installierbar.
- Standard-Port 8108, Standard-Admin-API-Key `xyz` (in `typesense.ini` änderbar).
- Änderungen greifen erst nach Neustart.

## Technische Notizen

- Inhalt: `resources/js/docs/typesense.md`
- Navigation/gültiger Slug: `config/docs.php` (Abschnitt **Search**)
- Test: `tests/Feature/DocsTest.php` prüft, dass `/guide/typesense` rendert.
- Datenquelle: `noxHWIDSpoofer` → `src-tauri/src/typesense.rs`, `typesense-versions.json`, `TypesenseInfoDialog.vue`.
