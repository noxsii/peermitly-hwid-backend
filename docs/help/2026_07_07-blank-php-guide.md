# Guide "Blank PHP" hinzugefügt

## Datum

2026-07-07

## Bereich

Hilfe / Guides, Sitemap

## Kurzbeschreibung

Neue Guide-Seite, die erklärt, wie man in Peermitly einen einfachen PHP-Ordner (ohne Framework) als Seite auf einer `.peer`-Domain bereitstellt.

## Was ist neu?

- Neue Doku-Seite unter `/guide/blank-php` (Datei `resources/js/docs/blank-php.md`).
- Eintrag im Guide-Menü in der Sektion **Sites** (`config/docs.php`), oberhalb von Laravel.
- Link zur neuen Seite in `public/sitemap.xml`.

## Warum wurde das geändert?

Bisher war nur das Anlegen von Framework-Projekten (Laravel, Symfony, Vue, …) dokumentiert. Peermitly kann aber auch einen vorhandenen, reinen PHP-Ordner ausliefern. Dafür fehlte eine Anleitung.

## Wie funktioniert es?

Ein "Blank PHP"-Site zeigt auf einen bereits vorhandenen Ordner. Es wird nichts angelegt oder verändert. Der Nutzer wählt eine Einstiegsdatei (`.php`):

- Standard ist `public/index.php`.
- Alternativ jede beliebige `.php`-Datei im Ordner (z. B. `index.php` im Wurzelverzeichnis).

Peermitly schreibt einen nginx-vhost, startet PHP-FPM und stellt die Seite unter `https://name.peer` bereit.

## Betroffene Bereiche

- Guide-/Hilfe-Seiten (`/guide/blank-php`)
- Guide-Navigation (`config/docs.php`)
- Sitemap (`public/sitemap.xml`)

## Wichtige Hinweise

- Blank PHP liefert immer einen **bestehenden Ordner** aus; es wird kein Projekt generiert. Für ein neues Projekt Laravel oder Symfony verwenden.
- Die Einstiegsdatei muss existieren und auf `.php` enden. Dateien außerhalb des Ordners oder versteckte Pfade sind nicht erlaubt.
- Der Guide-Slug lautet `blank-php`, da `php` bereits für "PHP versions & settings" belegt ist.

## Technische Notizen

- Doku-Slugs werden gegen `config/docs.php` validiert; unbekannte URLs liefern 404 (`DocsController`).
- Die Feature-Logik liegt im HWID-Spoofer-/Peermitly-Desktop-Projekt (`sites/scaffold.rs`): Framework-ID `php`, Modus `existing`, `docroot()` fällt ohne Entry auf `public/index.php` zurück.
- `DocsTest` prüft, dass Sitemap-Einträge und vorhandene `.md`-Dateien übereinstimmen (20 Tests grün).