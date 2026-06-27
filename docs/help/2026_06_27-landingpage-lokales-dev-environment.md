# LandingPage auf lokales Dev-Environment umgestellt

## Datum

2026-06-27

## Bereich

LandingPage, Marketing, Außendarstellung

## Kurzbeschreibung

Die komplette Startseite wurde inhaltlich neu ausgerichtet. Peermitly wird jetzt als schnelle, schöne lokale Entwicklungsumgebung präsentiert — ähnlich wie Herd, nur schneller und schöner. Das bisherige Thema (HWID-Spoofer für Spiele) ist vollständig entfernt.

## Was ist neu?

- Neue Positionierung: lokale Multi-Stack-Entwicklungsumgebung für macOS, Windows und Linux
- Alle Texte, Überschriften und Beispiele auf das neue Thema umgeschrieben
- Die Sektion "Games" heißt jetzt "Stacks" und zeigt unterstützte Frameworks (Laravel, Symfony, WordPress, Node.js, Django, Rails u. a.)
- Neue Feature-Liste: Instant Sites, Multi-Stack, Datenbanken & Dienste, sichere .test-Domains mit HTTPS, native Geschwindigkeit, Zero Config
- FAQ, Schritt-für-Schritt-Anleitung, Statistiken und Call-to-Action an das neue Thema angepasst

## Warum wurde das geändert?

Das Produkt wurde neu ausgerichtet. Die Startseite soll nun das lokale Entwicklungstool bewerben statt der bisherigen Spoofer-Funktion.

## Wie funktioniert es?

Die Startseite ist weiterhin in dieselben Abschnitte gegliedert (Navigation, Hero, Statistiken, Features, Ablauf, Stacks, Preise, FAQ, Call-to-Action, Footer). Nur die Inhalte und passende Symbole wurden ausgetauscht. Das visuelle Design bleibt unverändert.

## Betroffene Bereiche

- Startseite `/` (Inertia-Seite `landing/Index`)
- Alle Landing-Komponenten unter `resources/js/components/landing/`
- SEO-Titel und Meta-Beschreibung der Startseite

## Wichtige Hinweise

- Die Preis-Struktur (Day Pass, Weekly, Monthly) und die Preise bleiben unverändert. Nur die Leistungsbeschreibungen wurden auf das neue Thema angepasst.
- Das Design (Farben, Layout, Animationen) wurde bewusst nicht verändert.

## Technische Notizen

- Komponente `LandingGames.vue` wurde durch `LandingStacks.vue` ersetzt (Anker `#games` → `#stacks`).
- Import und Verwendung in `landing/Index.vue` entsprechend aktualisiert.
- Frontend-Build (`npm run build`) erfolgreich durchgelaufen.
