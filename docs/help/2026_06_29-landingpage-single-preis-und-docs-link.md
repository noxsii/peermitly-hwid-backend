# LandingPage: Einheitspreis und Docs-Link

## Datum

2026-06-29

## Bereich

LandingPage, Preise

## Kurzbeschreibung

Die Preis-Sektion der Startseite zeigt jetzt nur noch einen einzigen Plan: 4,99 € pro Monat mit allen Funktionen. Zusätzlich gibt es im Footer einen Link zu den Docs.

## Was ist neu?

- Ein einziger Preis: **4,99 € / Monat**, alle Features enthalten (keine Tiers, keine Add-ons)
- Neuer Footer-Link „Docs" (führt zu `/guide`)

## Warum wurde das geändert?

Das Produkt hat nur ein Preismodell. Die bisherigen drei Stufen (Day Pass, Weekly, Monthly) wurden durch eine klare Einzelkarte ersetzt.

## Wie funktioniert es?

Die Preis-Sektion zeigt eine zentrierte Karte „Peermitly Pro" mit Preis, Feature-Liste und „Get started"-Button. Der Footer enthält neben Privacy/Status nun auch „Docs".

## Betroffene Bereiche

- `resources/js/components/landing/LandingPricing.vue`
- `resources/js/components/landing/LandingFooter.vue`

## Wichtige Hinweise

- Es gibt nur diesen einen Preis. Alle Funktionen sind enthalten.
- „Cancel any time", Checkout über Stripe.

## Technische Notizen

- Die frühere Mehr-Tier-Struktur wurde vollständig entfernt; die Feature-Liste wird aus einem einzigen Array gerendert.
