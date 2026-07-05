# App-Screenshots auf der Landingpage

## Datum

2026-07-05

## Bereich

Landingpage, Marketing

## Kurzbeschreibung

Die Startseite zeigt jetzt echte App-Screenshots in einer neuen Sektion „A look inside" — ein großes App-Fenster plus ein Raster mit Feature-Bildern.

## Was ist neu?

- Neue Sektion **Product** (`#product`) zwischen Features und „How it works".
- Großes Vorschaubild im App-Fenster-Stil (mit Fenster-Punkten und weichem Glow).
- Vier Feature-Kacheln: Framework-Scaffolding, Datenbanken, Mail-Catcher und Sidebar-Anpassung.
- Neuer Navigationspunkt **Product**.
- Voll responsiv: auf dem Handy stapeln sich Bild und Kacheln untereinander.

## Warum wurde das geändert?

Echte Screenshots zeigen sofort, wie die App aussieht und was sie kann.

## Betroffene Bereiche

- `resources/js/components/landing/LandingShowcase.vue` (neu)
- `resources/js/pages/landing/Index.vue`, `LandingNav.vue`

## Wichtige Hinweise

- Bilder liegen in `public/images/screenshots/landing/`.
- Im großen Screenshot wurde der lokale Benutzerpfad (Fehlermeldung) unkenntlich gemacht.

## Technische Notizen

- Bild-URLs werden gebunden (`:src`), damit Vite die Public-Assets nicht als Modul auflöst.
- `loading="lazy"` auf allen Bildern.
