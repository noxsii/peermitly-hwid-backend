# Download-Button auf der Landingpage

## Datum

2026-07-06

## Bereich

Landingpage, Download

## Kurzbeschreibung

Die Startseite hat jetzt eine **Download**-Sektion mit einem Button für die macOS-App (.dmg) und einem Hinweis zur Testphase.

## Was ist neu?

- Neue Sektion **Download** (`#download`) mit großem Download-Button.
- Link zur Datei: `https://peermitly.de/storage/releases/peermitly_universal.dmg`.
- Hinweis, dass die App aktuell in der **offenen Testphase** und noch nicht notarisiert ist.
- Anleitung mit Kopier-Button für den einmaligen Befehl: `xattr -cr /Applications/peermitly.app`.
- Neuer Navigationspunkt **Download**.
- Voll responsiv.

## Warum wurde das geändert?

Nutzer sollen die App direkt herunterladen können — inklusive dem nötigen Schritt, um die noch nicht signierte App unter macOS zu öffnen.

## Wie funktioniert es?

1. Auf **Download for macOS** klicken → die `.dmg` wird geladen.
2. App in den **Applications**-Ordner ziehen.
3. Einmalig im Terminal `xattr -cr /Applications/peermitly.app` ausführen (Kopier-Button daneben).
4. App normal starten.

## Betroffene Bereiche

- `resources/js/components/landing/LandingDownload.vue` (neu)
- `resources/js/pages/landing/Index.vue`, `LandingNav.vue`

## Wichtige Hinweise

- Der `xattr`-Befehl entfernt das Quarantäne-Flag, damit macOS die App trotz fehlender Notarisierung öffnet.
- DMG-URL bei neuen Releases ggf. anpassen.

## Technische Notizen

- Kopierfunktion über `useCopyToClipboard`.
