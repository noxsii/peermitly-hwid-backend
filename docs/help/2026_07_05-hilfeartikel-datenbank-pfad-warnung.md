# Hilfeartikel für die Datenbank-Pfad-Warnung

## Datum

2026-07-05

## Bereich

Dokumentation, Troubleshooting, Datenbanken

## Kurzbeschreibung

Für die neue Warnung „Shell nutzt nicht die verwaltete Datenbank" gibt es jetzt einen Hilfeartikel, auf den die App verlinkt.

## Was ist neu?

- Neue Doku-Seite unter `/guide/homebrew-database-path` (Bereich **Troubleshooting**).
- Erklärt, wie man den Homebrew-Pfad in der Shell nach vorne bringt, damit `mysql` / `psql` / `mongosh` die verwaltete Datenbank verwenden.

## Warum wurde das geändert?

In der App wurde — wie bei PHP und Node — eine Warnung für Datenbanken ergänzt. Der „How to fix this"-Link zeigt auf diese Seite.

## Wie funktioniert es?

1. Erscheint auf einem Datenbank-Screen die Warnung, öffnet „How to fix this" diesen Artikel.
2. Der Artikel zeigt den passenden Client (MariaDB/MySQL → `mysql`, PostgreSQL → `psql`, MongoDB → `mongosh`) und wie man `/opt/homebrew/bin` in der `PATH` nach vorne holt.

## Betroffene Bereiche

- Docs-Menü (Sidebar), Bereich **Troubleshooting**
- Neue Seite `/guide/homebrew-database-path`

## Wichtige Hinweise

- Die App verlinkt aus `DbActiveWarning` auf `https://peermitly.de/guide/homebrew-database-path` — der Slug muss exakt passen.

## Technische Notizen

- Inhalt: `resources/js/docs/homebrew-database-path.md`
- Navigation/gültiger Slug: `config/docs.php` (Abschnitt **Troubleshooting**)
- Test: `tests/Feature/DocsTest.php`
- Quelle: `noxHWIDSpoofer` → `src/components/dashboard/database/DbActiveWarning.vue`.
