# Meilisearch-Anleitung hinzugefügt

## Datum

2026-07-02

## Bereich

Dokumentation, Search

## Kurzbeschreibung

Es gibt jetzt eine eigene Hilfe-Seite für das neue **Meilisearch**-Feature.

## Was ist neu?

- Neue Doku-Seite **Meilisearch** unter `/guide/meilisearch`.
- Neuer Menü-Bereich **Search** in der Docs-Sidebar.
- Die Anleitung erklärt Installation, Start/Stop/Restart, Port, Config, Logs, Ressourcen und die Anbindung an die eigene App.

## Warum wurde das geändert?

Meilisearch wurde als neues System hinzugefügt und war noch nicht dokumentiert.

## Wie funktioniert es?

1. In den Docs im Bereich **Search** den Punkt **Meilisearch** öffnen.
2. Die Seite beschreibt: eine der drei aktuellsten Versionen installieren, Dienst starten, Dashboard öffnen (`http://localhost:7700`), Port/Config anpassen und Logs ansehen.
3. Für Laravel-Apps sind die passenden Scout-`.env`-Werte angegeben.

## Betroffene Bereiche

- Docs-Menü (Sidebar), neuer Bereich **Search**
- Neue Seite `/guide/meilisearch`

## Wichtige Hinweise

- Nur eine Version gleichzeitig installierbar.
- Port- und Config-Änderungen greifen erst nach Neustart.
- Standard-Port ist 7700.

## Technische Notizen

- Inhalt: `resources/js/docs/meilisearch.md`
- Navigation/gültiger Slug: `config/docs.php` (Abschnitt **Search**)
- Test: `tests/Feature/DocsTest.php` prüft, dass `/guide/meilisearch` rendert.
