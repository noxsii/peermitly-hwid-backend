# PHP-Extensions pro Version verwalten

## Datum

2026-07-10

## Bereich

Dokumentation (Guide), PHP-Verwaltung

## Kurzbeschreibung

Die Peermitly-App kann jetzt PHP-Extensions pro installierter PHP-Version verwalten: installieren, aktivieren, deaktivieren und entfernen. Der Guide "PHP versions & settings" wurde um einen Abschnitt "Manage extensions" mit Screenshot erweitert.

## Was ist neu?

- Neuer Abschnitt **"Manage extensions"** im Guide unter `https://peermitly.de/guide/php`.
- Screenshot `php_extensions.png` zeigt den Extensions-Dialog.
- Der Einleitungs-Überblick des Guides nennt die Extension-Verwaltung als neue Fähigkeit.

## Warum wurde das geändert?

Bisher mussten Extensions manuell über `pecl` und `php.ini`-Bearbeitung installiert werden. Die App übernimmt das jetzt vollständig — der Guide muss das erklären.

## Wie funktioniert es?

1. Im **PHP**-Bereich der App auf das **Puzzle-Symbol** einer installierten Version klicken.
2. Der Dialog zeigt eine kuratierte Liste (Redis, Xdebug, ImageMagick, MongoDB, APCu, Memcached, Swoole) plus alle selbst installierten Extensions mit Version und Status.
3. **Install** baut die Extension für genau diese PHP-Version (Build-Log live sichtbar), aktiviert sie automatisch und startet FPM neu.
4. **Enable/Disable** schaltet eine installierte Extension um, ohne sie zu deinstallieren (z. B. Xdebug nur beim Debuggen).
5. **Entfernen** deinstalliert die Extension und räumt den `php.ini`-Eintrag auf.
6. Über das Eingabefeld unten lässt sich jede beliebige Extension per **pecl-Name** installieren.

## Betroffene Bereiche

- Guide-Seite `resources/js/docs/php.md` (Abschnitt "Manage extensions")
- Screenshot `public/images/screenshots/php_extensions.png`

## Wichtige Hinweise

- Extensions gelten **pro PHP-Version** — für eine andere Version muss die Extension dort separat installiert werden.
- Zum Kompilieren sind die **Xcode Command Line Tools** nötig (`xcode-select --install`).
- Änderungen greifen sofort: Die App schreibt `php.ini` und startet FPM automatisch neu.
- Kontrolle im Terminal: `php -m` listet die aktiven Extensions.

## Beispiel

Ein Nutzer braucht Redis für seine Laravel-App auf PHP 8.4: Puzzle-Symbol bei PHP 8.4 klicken, bei "Redis" auf **Install** — nach dem Build ist die Extension aktiv und `php -m` zeigt `redis`.

## Technische Notizen

- Keine neue Guide-Seite, kein neuer Slug — bestehende Seite `php` erweitert, Sitemap unverändert.
- App-Seite (Tauri): Installation über das versionseigene `pecl`-Binary, Aktivierung per `extension=`/`zend_extension=`-Zeile in der `php.ini` der jeweiligen Version; kuratierte Liste aus `php-extensions.json`.