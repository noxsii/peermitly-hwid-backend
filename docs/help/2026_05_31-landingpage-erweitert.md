# Landingpage erweitert

## Datum

2026-05-31

## Bereich

Startseite, Marketing, Öffentlicher Bereich

## Kurzbeschreibung

Die öffentliche Startseite wurde deutlich ausgebaut und erklärt das Produkt jetzt
vollständiger. Außerdem wurden Suchmaschinen- und Social-Media-Informationen (Meta-Tags)
ergänzt.

## Was ist neu?

- Vertrauensleiste direkt unter dem Hero (HWID-Lock, Vollständig gehostet, Aktivierungs-API, Uptime)
- Abschnitt „How it works" mit drei Schritten
- Abschnitt „Who it's for" mit vier Anwendungsfällen
- API-Abschnitt mit echtem Code-Beispiel und Kopier-Button
- FAQ-Abschnitt mit aufklappbaren Fragen
- Erweiterter Footer mit Spalten (Product, Resources, Legal)
- Sanfte Einblend-Animationen beim Scrollen (respektiert reduzierte Bewegung)
- Vollständige Meta-Tags: Beschreibung, Canonical, Open Graph und Twitter-Card

## Warum wurde das geändert?

Die alte Startseite war sehr knapp und erklärte das Produkt kaum. Neue Besucher und
Suchmaschinen erhalten jetzt deutlich mehr Kontext, was Peermitly leistet.

## Wie funktioniert es?

Die Seite ist weiterhin unter „/" erreichbar. Über die Navigation oben gelangt man per
Ankerlinks direkt zu „How it works", „API" und „FAQ". Im API-Abschnitt kann das
Beispiel mit einem Klick kopiert werden.

## Betroffene Bereiche

- Startseite (`/`)
- Navigation und Footer der Startseite

## Wichtige Hinweise

- Die Links „Changelog" und „Hilfe" sind bewusst nicht im öffentlichen Footer, da sie
  nur für angemeldete Nutzer erreichbar sind.
- Für die Social-Media-Vorschau muss unter `public/og-image.png` ein Bild (1200×630)
  hinterlegt sein.

## Technische Notizen

Neue Vue-Komponenten unter `resources/js/components/landing/`, Typen in
`resources/js/types/landing.ts`, zwei Composables (`useScrollReveal`,
`useCopyToClipboard`) auf Basis von `@vueuse/core`. Die Meta-URLs werden
umgebungssicher im `LandingController` über `url()` erzeugt.