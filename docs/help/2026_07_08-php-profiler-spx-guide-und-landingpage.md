# PHP Profiler (SPX): Guide-Seite und LandingPage-Infos

## Datum

2026-07-08

## Bereich

Dokumentation (Guide), LandingPage

## Kurzbeschreibung

Für den neuen PHP Profiler der Peermitly-App (basierend auf SPX) gibt es jetzt eine eigene Guide-Seite unter `/guide/profiler`. Zusätzlich wird der Profiler auf der LandingPage vorgestellt: als Feature-Karte im Features-Bereich und als neuer Eintrag im FAQ.

## Was ist neu?

- **Neue Guide-Seite "Profiler"** in der Navigation unter **Tools** (mit Pro-Kennzeichnung), erreichbar unter `https://peermitly.de/guide/profiler`.
- **Feature-Karte "PHP profiler built in"** im Features-Bereich der LandingPage.
- **FAQ-Eintrag "Can I profile my PHP code?"** auf der LandingPage.
- Die Guide-Seite ist in der `sitemap.xml` eingetragen.

## Warum wurde das geändert?

Die App hat einen neuen PHP Profiler bekommen (Pro-Feature). Nutzer brauchen eine verständliche Anleitung, und Interessenten sollen das Feature auf der LandingPage finden können.

## Wie funktioniert es?

Der Guide beschreibt den kompletten Ablauf des Profilers in der App:

1. **Installieren:** In der App unter **Settings → Profiler** auf **Install** klicken. Peermitly lädt die neueste SPX-Version und baut die Extension für die aktive PHP-Version (Build-Log live sichtbar). Voraussetzung: Xcode Command Line Tools.
2. **Aktivieren:** Haken bei "Enable profiler for all PHP sites" setzen. Peermitly lädt die Extension, erzeugt einen privaten Zugriffsschlüssel, fügt jeder PHP-Site die Route `/peermitly-profiler` hinzu und startet PHP-FPM neu.
3. **Profilen:** Im Browser `https://<site>.peer/peermitly-profiler` öffnen (oder die Profiler-Links in der App nutzen), den **Profiling**-Schalter aktivieren und die Seite normal benutzen. Jede Anfrage erscheint in der Requests-Liste mit Laufzeit, Speicherverbrauch und Anzahl der Funktionsaufrufe. Ein Klick öffnet den vollständigen Bericht (Flame Graph, Timeline).
4. **Einstellungen:** Auto start, interne PHP-Funktionen, Sampling, maximale Tiefe und Metriken lassen sich direkt in der Profiler-Oberfläche anpassen.

## Betroffene Bereiche

- Guide-Navigation (`config/docs.php`, neue Sektion "Tools" → "Profiler")
- Neue Guide-Seite `resources/js/docs/profiler.md`
- LandingPage: Features-Bereich (`LandingFeatures.vue`) und FAQ (`LandingFaq.vue`)
- `public/sitemap.xml`
- Screenshot `public/images/screenshots/php-spx.png` wird im Guide verwendet

## Wichtige Hinweise

- Der Profiler ist ein **Pro-Feature** — der Guide und die Navigation kennzeichnen das entsprechend.
- Der Profiler funktioniert nur für PHP-Sites; Node-Sites (Vue, React, Nuxt, Astro) werden nicht profiliert.
- Alles läuft lokal: Zugriff nur von localhost, geschützt durch einen automatisch verwalteten Schlüssel; Profildaten bleiben auf dem Mac.
- Nach einem PHP-Versionswechsel muss der Profiler einmal neu installiert werden (die App weist darauf hin).

## Beispiel

Ein Nutzer möchte wissen, warum seine Laravel-Startseite langsam ist. Er installiert und aktiviert den Profiler in den App-Einstellungen, öffnet `https://meine-app.peer/peermitly-profiler`, schaltet Profiling ein und lädt die Startseite neu. In der Requests-Liste erscheint die Anfrage mit z. B. "254 ms · 5.2 MB · 30.6k calls" — ein Klick zeigt im Flame Graph, welche Funktion die Zeit verbraucht.

## Technische Notizen

- Neuer Slug `profiler` in `config/docs.php` (Sektion Tools, `pro => true`); der bestehende Sitemap-Test deckt den neuen Slug automatisch mit ab.
- Neuer Feature-Test "the profiler guide slug renders" in `tests/Feature/DocsTest.php`.
- Die App-Seite (Tauri) baut SPX aus dem offiziellen GitHub-Repo für die aktive Homebrew-PHP-Version und liefert eine eigene Peermitly-Web-UI über `spx.http_ui_assets_dir` aus.