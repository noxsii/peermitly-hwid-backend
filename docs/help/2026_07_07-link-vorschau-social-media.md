# Link-Vorschau für Slack, LinkedIn und andere Plattformen

## Datum

2026-07-07

## Bereich

Website / Landingpage / SEO

## Kurzbeschreibung

Links zu peermitly.de zeigen jetzt eine Vorschau (Titel, Beschreibung, Bild), wenn sie in Slack, LinkedIn, Discord, X/Twitter oder anderen Diensten geteilt werden.

## Was ist neu?

- Alle Seiten liefern jetzt serverseitig Open-Graph- und Twitter-Card-Meta-Tags aus (Titel, Beschreibung, Vorschaubild).
- Ein Vorschaubild `og-image.png` (1200×630 Pixel) wurde erstellt und liegt öffentlich unter `https://peermitly.de/og-image.png`.

## Warum wurde das geändert?

Beim Teilen eines peermitly.de-Links wurde keine Vorschau angezeigt. Die Meta-Tags existierten zwar in der Landingpage, wurden aber nur per JavaScript im Browser gerendert. Die Crawler von Slack und LinkedIn führen kein JavaScript aus und sahen daher eine leere Seite ohne Titel und Bild. Zusätzlich war das hinterlegte Vorschaubild nicht vorhanden (404).

## Wie funktioniert es?

1. Das Root-Template `resources/views/app.blade.php` enthält jetzt Standard-Meta-Tags, die bei jedem Seitenaufruf direkt im HTML stehen.
2. Die Open-Graph- und Twitter-Tags sind statisch (ohne `data-inertia`-Attribut) und bleiben daher auf allen Seiten dauerhaft im Dokument — Inertia rührt sie nicht an.
3. Titel und Beschreibung können Seiten weiterhin überschreiben: der Titel über den Inertia-`<Head>`-Component, die Beschreibung über den `head-key`/`data-inertia`-Mechanismus.

## Betroffene Bereiche

- Landingpage (Startseite) und alle weiteren Inertia-Seiten (Docs, Changelog, Impressum usw.)
- `resources/views/app.blade.php`
- `public/og-image.png` (neu)

## Wichtige Hinweise

- Slack und LinkedIn cachen Link-Vorschauen. Nach dem Deployment die Vorschau prüfen mit dem LinkedIn Post Inspector (https://www.linkedin.com/post-inspector/) — dieser erneuert gleichzeitig den LinkedIn-Cache. In Slack ggf. den Link mit angehängtem `?v=2` testen.
- Wenn sich das Vorschaubild ändern soll, einfach `public/og-image.png` ersetzen (1200×630 Pixel empfohlen).

## Beispiel

Ein in Slack geteilter Link `https://peermitly.de` zeigt jetzt: Titel „Peermitly — The fast, beautiful local dev environment", die Kurzbeschreibung und einen Screenshot der App.

## Technische Notizen

- OG/Twitter-Tags stehen ohne `data-inertia`-Attribut im Blade-Template — Inertias `<Head>` verwaltet nur Tags mit diesem Attribut und lässt statische Tags unangetastet. Der zuvor redundante OG-Block in `landing/Index.vue` wurde entfernt.
- `<!DOCTYPE html>` fehlte im Root-Template und wurde ergänzt.
- Tests: `tests/Feature/Landing/LandingPageTest.php` prüft jetzt, dass die OG-Tags im servergerenderten HTML stehen und `og-image.png` existiert.