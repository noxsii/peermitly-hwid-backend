# Landingpage um neue Features ergänzt

## Datum

2026-07-02

## Bereich

Landingpage, Marketing

## Kurzbeschreibung

Die Startseite zeigt jetzt die neuen Funktionen: das **Debug-Tool** und das **Scaffolding** für Vue-, React- und Astro-Apps.

## Was ist neu?

- **Features-Bereich:** zwei neue Karten — „Scaffold new apps" (Vue/React/Astro) und „Live debug tool" (dd/dump/peer).
- **Supported stacks:** „Vue" und „React" zur Framework-Leiste hinzugefügt (Astro war bereits gelistet).
- **FAQ:** zwei neue Fragen — zum Erstellen neuer Front-end-Apps und zum Debug-Tool. Die Framework-Antwort nennt jetzt auch Vue, React und Astro.

## Warum wurde das geändert?

Die neuen Funktionen (Debug-Tool, App-Scaffolding) waren in der Doku beschrieben, aber auf der Startseite nicht sichtbar. So passt das Marketing zum tatsächlichen Funktionsumfang.

## Betroffene Bereiche

- `resources/js/components/landing/LandingFeatures.vue`
- `resources/js/components/landing/LandingStacks.vue`
- `resources/js/components/landing/LandingFaq.vue`

## Wichtige Hinweise

- Der Docs-Link in der Navigation (`/guide`) war bereits vorhanden.
- Reine Frontend-Änderung: Nach dem Deploy `npm run build` bzw. im Dev `npm run dev` ausführen, damit die Änderungen sichtbar sind.