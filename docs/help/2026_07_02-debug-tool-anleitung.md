# Debug-Tool-Anleitung hinzugefügt

## Datum

2026-07-02

## Bereich

Dokumentation, Debug

## Kurzbeschreibung

Es gibt jetzt eine ausführliche Hilfe-Seite für das **Debug-Tool** von Peermitly.

## Was ist neu?

- Neue Doku-Seite **Debug** unter `/guide/debug`.
- Neuer Menü-Bereich **Tools** in der Docs-Sidebar, der die Debug-Seite enthält.
- Die Anleitung erklärt, wie man Werte aus PHP-Sites live im Debug-Fenster ansieht.

## Warum wurde das geändert?

Das Debug-Tool war noch nicht dokumentiert. Nutzer sollen wissen, wie sie mit `dd()`, `dump()` und `peer()` Werte anzeigen und durchsuchen.

## Wie funktioniert es?

1. Debug-Fenster öffnen und einschalten (Schalter/Punkt zeigt an/aus).
2. Im PHP-Code `dd()`, `dump()` oder `peer()` verwenden.
3. Seite im Browser laden — die Werte erscheinen live im Debug-Fenster.
4. Über die Suche lassen sich Dumps filtern; Treffer werden hervorgehoben.

## Betroffene Bereiche

- Docs-Menü (Sidebar), neuer Bereich **Tools**
- Neue Seite `/guide/debug`

## Wichtige Hinweise

- `dd()` beendet das Skript (dump & die), `dump()` und `peer()` laufen weiter und geben den Wert zurück.
- Ist Debug ausgeschaltet, passiert bei den Aufrufen nichts.
- Funktioniert in jeder Peermitly-PHP-Site ohne Installation (Laravel, Symfony, reines PHP).

## Beispiel

```php
peer($items);        // an Debug senden, weiterlaufen
dump($items->sum()); // zweiten Wert ansehen
dd($request->all()); // ansehen und stoppen
```

## Technische Notizen

- Inhalt: `resources/js/docs/debug.md`
- Navigation/gültiger Slug: `config/docs.php` (Abschnitt **Tools**)
- Screenshots: `public/images/screenshots/dump_1.png`, `dump_2.png`
- Test: `tests/Feature/DocsTest.php` prüft, dass `/guide/debug` rendert.
- Datenquelle: `noxHWIDSpoofer` → `src-tauri/assets/dump-prepend.php`, `src-tauri/src/dumps.rs`, `src/services/dumps.ts`.