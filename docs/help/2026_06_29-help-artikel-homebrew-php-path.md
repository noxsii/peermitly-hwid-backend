# Help-Artikel: Shell nutzt nicht das Homebrew-PHP

## Datum

2026-06-29

## Bereich

Dokumentation (/guide), PHP-Verwaltung

## Kurzbeschreibung

Neuer Help-Artikel in den Docs, der die Warnung „Your shell is not using the Homebrew PHP" aus dem PHP-Bereich der App erklärt und Schritt für Schritt löst.

## Was ist neu?

- Neue Doc-Seite unter `/guide/homebrew-php-path`
- Neue Docs-Sektion „Troubleshooting" in der Navigation

## Warum wurde das geändert?

Die App zeigt diese Warnung, wenn im Terminal ein anderes PHP gefunden wird als das von Homebrew verwaltete. Nutzer brauchten eine klare Anleitung zur Lösung.

## Wie funktioniert es?

Der Artikel erklärt:

1. Warum die Warnung erscheint (ein anderes PHP liegt im `PATH` vor dem Homebrew-PHP).
2. Eine Version in der App aktivieren.
3. `/opt/homebrew/bin` im Shell-PATH nach vorne holen (`~/.zshrc` bzw. `~/.bash_profile`).
4. Shell neu laden und mit `which php` prüfen.
5. In der App erneut prüfen.

## Betroffene Bereiche

- Docs-Navigation (`config/docs.php`): neue Sektion „Troubleshooting"
- Neue Datei `resources/js/docs/homebrew-php-path.md`

## Wichtige Hinweise

- Der Artikel ist bewusst werkzeug-neutral formuliert: das störende PHP kann von einem System-PHP, einem anderen Dev-Tool oder einem Versionsmanager stammen — nicht zwingend Herd.
- Die App ermittelt das aktive PHP über `command -v php` in der Login-Shell; entscheidend ist die Reihenfolge im `PATH`.

## Technische Notizen

- Erwarteter Pfad: `/opt/homebrew/bin/php`. Die App vergleicht den von der Shell aufgelösten Pfad mit dem Homebrew-Pfad und zeigt die Warnung bei Abweichung.
- Quelle der Meldung im Desktop-Projekt: `src/views/dashboard/php/PhpManager.vue`, Logik in `src-tauri/src/php/homebrew.rs`.
