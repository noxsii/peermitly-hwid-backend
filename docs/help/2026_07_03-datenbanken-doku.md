# Datenbank-Anleitungen hinzugefügt

## Datum

2026-07-03

## Bereich

Dokumentation, Datenbanken

## Kurzbeschreibung

Es gibt jetzt einen eigenen Menübereich **Databases** in den Docs mit Anleitungen für **MariaDB**, **MySQL**, **PostgreSQL** und **MongoDB**.

## Was ist neu?

- Neuer Docs-Bereich **Databases** mit vier Seiten:
  - `/guide/mariadb`
  - `/guide/mysql`
  - `/guide/postgresql`
  - `/guide/mongodb`
- Jede Seite erklärt Installation, Versionen, Port, Start/Stop/Restart, Config, Logs, Ressourcen und die Anbindung an die App.

## Warum wurde das geändert?

Die Datenbanken waren in Peermitly nutzbar, aber nicht dokumentiert.

## Wie funktioniert es?

1. Im Docs-Bereich **Databases** die gewünschte Datenbank öffnen.
2. Eine Version installieren, Dienst starten.
3. Bei SQL-Datenbanken **Root-Zugang aktivieren** und optional **Adminer** öffnen.
4. Die passenden `.env`-Werte in die App übernehmen.

## Betroffene Bereiche

- Docs-Menü (Sidebar), neuer Bereich **Databases**

## Wichtige Hinweise

- Ports: MariaDB 3306, MySQL 3307, PostgreSQL 5432, MongoDB 27017.
- Root-Zugang und Adminer gibt es für die SQL-Datenbanken; MongoDB läuft lokal ohne Authentifizierung und ohne Adminer.
- Nur eine Version pro Datenbank gleichzeitig installierbar.
- Zugangsdaten `root` / `root` nur für lokale Entwicklung.

## Technische Notizen

- Inhalte: `resources/js/docs/{mariadb,mysql,postgresql,mongodb}.md`
- Navigation/gültige Slugs: `config/docs.php` (Abschnitt **Databases**)
- Test: `tests/Feature/DocsTest.php` (Dataset über alle vier Slugs)
- Datenquelle: `noxHWIDSpoofer` → `src-tauri/database-versions.json`, `src-tauri/src/database.rs`, `DbInfoDialog.vue`.
