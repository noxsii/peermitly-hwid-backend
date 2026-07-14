# Download im Dashboard verfügbar

## Datum

2026-07-14

## Bereich

Dashboard

## Kurzbeschreibung

Der macOS-Download ist jetzt auch nach dem Login direkt im Dashboard verfügbar – mit demselben Link und denselben Hinweisen wie auf der Landingpage.

## Was ist neu?

Unterhalb der bestehenden Karten (Kontostatus, Abo, Sicherheitscode) erscheint im Dashboard der komplette Download-Bereich:

- Button „Download for macOS“ mit der universellen .dmg-Datei
- Hinweis „Universal · Apple Silicon & Intel · .dmg“
- Hinweiskarte zur offenen Testphase inkl. `xattr`-Befehl und Kopier-Button

## Warum wurde das geändert?

Angemeldete Nutzer mussten bisher zurück auf die Landingpage, um die App herunterzuladen. Der Download gehört dorthin, wo Nutzer nach dem Login landen.

## Wie funktioniert es?

1. Einloggen und Dashboard öffnen.
2. Nach unten zum Download-Bereich scrollen.
3. Auf „Download for macOS“ klicken – die .dmg wird geladen.
4. App nach „Programme“ ziehen.
5. Bei Bedarf den angezeigten `xattr`-Befehl kopieren und einmalig im Terminal ausführen, danach App normal starten.

## Betroffene Bereiche

- Dashboard (`/dashboard`)
- Landingpage (unverändert, gleiche Komponente)

## Wichtige Hinweise

- Landingpage und Dashboard nutzen dieselbe Komponente. Eine neue Release-URL muss weiterhin nur an einer Stelle angepasst werden (`resources/js/components/landing/LandingDownload.vue`).
- Es gibt weiterhin keinen Login-Zwang für den Download: Der Link ist identisch mit dem öffentlichen Link.

## Technische Notizen

`resources/js/pages/Dashboard.vue` bindet `LandingDownload.vue` direkt ein. Kein neuer Endpunkt, keine neuen Props, kein Backend-Change. Abgedeckt durch `tests/Browser/DashboardDownloadTest.php`.