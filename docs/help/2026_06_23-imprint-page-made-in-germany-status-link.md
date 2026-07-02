# Imprint page, Made in Germany badge and status link

## Datum

2026-06-23

## Bereich

Landing page, Legal, Footer

## Kurzbeschreibung

A new Imprint page was added, the footer now links to Privacy, Imprint and the
status page, and a clean "Made in Germany" badge was placed in the footer.

## Was ist neu?

- New page reachable at `/imprint` (route name `imprint`).
- Footer now shows three legal/info links: Privacy, Imprint and Status.
- The Status link opens the external status page
  `https://peermitly.betteruptime.com/` in a new tab.
- A small "Made in Germany" badge with a German flag sits in the footer bottom
  bar next to the copyright line.

## Warum wurde das geändert?

- A German imprint is legally required (§ 5 DDG).
- Users should be able to reach the live service status quickly.
- The "Made in Germany" badge signals trust and origin of the product.

## Wie funktioniert es?

- The Imprint page is opened through the footer "Imprint" link or directly at
  `/imprint`.
- The page is structured along the German legal requirements (service provider,
  contact, representative, register entry, VAT ID, content responsibility, EU
  dispute resolution). The company data fields are still empty and need to be
  filled in.
- The "Status" footer link points to the external uptime page.

## Betroffene Bereiche

- `routes/web.php`
- `app/Http/Controllers/ImprintController.php`
- `../../resources/js/pages/imprint/Index.vue`
- `resources/js/components/landing/LandingFooter.vue`

## Wichtige Hinweise

- The imprint still needs the real company data (name, address, phone, email,
  representative, register and VAT details) before it is legally valid.
- Page content and routes are in English; only the legal references (§ 5 DDG,
  § 18 MStV, § 27 a UStG) keep their German legal names.

## Technische Notizen

- Mirrors the existing Privacy page setup (controller renders an Inertia page,
  layout disabled via `defineOptions({ layout: "" })`).
- Covered by `tests/Feature/ImprintPageTest.php`.
