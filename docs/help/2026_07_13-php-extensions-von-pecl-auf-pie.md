# PHP-Extensions: Umstellung von pecl auf PIE

## Datum

2026-07-13

## Bereich

PHP-Verwaltung, Dokumentation (Guide)

## Kurzbeschreibung

Die Extension-Verwaltung der Peermitly-App installiert und baut PHP-Extensions jetzt über **PIE** (PHP Installer for Extensions) statt über das alte `pecl`. Der Guide "PHP versions & settings" und der Extensions-Screenshot wurden entsprechend angepasst.

## Was ist neu?

- Extensions werden intern über **PIE** installiert (der offizielle Nachfolger von `pecl`).
- Freie Installation erfolgt jetzt über den **PIE-Paketnamen** im Format `vendor/package` (z. B. `xdebug/xdebug`) statt über den reinen pecl-Namen.
- Der Screenshot `php_extensions.png` wurde ersetzt.

## Warum wurde das geändert?

`pecl` gilt als veraltet und wird nicht mehr aktiv weiterentwickelt. **PIE** ist der offizielle, von PHP gepflegte Nachfolger, nutzt Composer-artige Paketnamen und löst Abhängigkeiten sauberer auf. Damit sind Extension-Installationen zuverlässiger und zukunftssicher.

## Wie funktioniert es?

Die Bedienung im Extensions-Dialog bleibt gleich:

1. Im **PHP**-Bereich auf das **Puzzle-Symbol** einer installierten Version klicken.
2. Aus der kuratierten Liste eine Extension per **Install** auswählen — PIE baut sie für genau diese PHP-Version (Build-Log live sichtbar), aktiviert sie und startet FPM neu.
3. **Enable/Disable** schaltet eine installierte Extension um, **Entfernen** deinstalliert sie.
4. Über das Eingabefeld unten lässt sich jede Extension per **PIE-Paketname** (`vendor/package`, z. B. `xdebug/xdebug`) installieren.

## Betroffene Bereiche

- Guide-Seite `resources/js/docs/php.md` (Abschnitt "Manage extensions")
- Screenshot `public/images/screenshots/php_extensions.png` (ersetzt)
- App-Seite (Tauri): Extension-Installation von `pecl` auf PIE umgestellt

## Wichtige Hinweise

- Für Nutzer ändert sich die Bedienung **nicht** — nur der freie Installationsname folgt jetzt dem PIE-Format `vendor/package` statt dem reinen pecl-Namen.
- Zum Kompilieren sind weiterhin die **Xcode Command Line Tools** nötig (`xcode-select --install`).
- Extensions gelten weiterhin **pro PHP-Version**.
- Kontrolle im Terminal: `php -m` listet die aktiven Extensions.

## Beispiel

Xdebug auf PHP 8.4 installieren: Puzzle-Symbol bei PHP 8.4 klicken, bei "Xdebug" auf **Install** — oder im Eingabefeld `xdebug/xdebug` eingeben. PIE baut und aktiviert die Extension, danach zeigt `php -m` den Eintrag `xdebug`.

## Technische Notizen

- App-Seite (Tauri): Statt des versionseigenen `pecl`-Binaries wird jetzt PIE aufgerufen; Aktivierung weiterhin per `extension=`/`zend_extension=`-Zeile in der `php.ini` der jeweiligen Version.
- Kuratierte Liste unverändert (`php-extensions.json`), nur das Installations-Backend wurde getauscht.