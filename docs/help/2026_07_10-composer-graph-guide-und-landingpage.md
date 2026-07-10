# Composer-Graph: Guide und Landingpage

## Datum

2026-07-10

## Bereich

Dokumentation (Guide), Landingpage

## Kurzbeschreibung

Die Peermitly-App kann eine `composer.lock` als interaktiven Abhängigkeits-Graphen darstellen. Dafür gibt es jetzt einen eigenen Guide "Composer graph" (Pro-Feature) mit Screenshot, und das Feature wird auf der Landingpage genannt.

## Was ist neu?

- Neue Guide-Seite **"Composer graph"** unter `https://peermitly.de/guide/composer` (als **Pro**-Feature markiert, Bereich "Tools").
- Screenshot `composer_lock.png` zeigt den Graphen neben der `composer.lock`.
- Neue Feature-Kachel **"Composer dependency graph"** auf der Landingpage.

## Warum wurde das geändert?

Eine `composer.lock` hat oft tausende Zeilen JSON. Der Graph macht auf einen Blick sichtbar, was ein Projekt an Paketen zieht, was Prod und was Dev ist, welche PHP-Version und Extensions nötig sind und wie aktuell die Abhängigkeiten sind. Das Feature war bisher nirgends dokumentiert oder beworben.

## Wie funktioniert es?

1. Ein Projekt mit vorhandener `composer.lock` in Peermitly öffnen — der Graph wird automatisch gezeichnet.
2. Links steht die rohe `composer.lock`, rechts der Graph.
3. Über die Schalter **Prod** und **Dev** lassen sich die Paketgruppen ein- und ausblenden (Prod = orange, Dev = dunkel).
4. Klick auf einen Punkt öffnet die Paket-Details: Name, Version, Beschreibung, Lizenz, Release-Datum und Link zur Homepage.
5. Die obere Leiste fasst das Lock-File zusammen: Anzahl Prod-/Dev-Pakete, geforderte PHP-Version, benötigte `ext-*`-Extensions und Datumsspanne der Pakete.
6. Der Tab **Freshness** zeigt, wie aktuell die Abhängigkeiten sind, und hebt lange nicht aktualisierte Pakete hervor.

## Betroffene Bereiche

- Neue Guide-Datei `resources/js/docs/composer.md`
- Registrierung in `config/docs.php` (Bereich "Tools", `'pro' => true`)
- `public/sitemap.xml` (neuer Eintrag `/guide/composer`)
- `resources/js/components/landing/LandingFeatures.vue` (neue Feature-Kachel)
- Screenshot `public/images/screenshots/composer_lock.png`

## Wichtige Hinweise

- **Pro-Feature** — nur mit Peermitly Pro verfügbar.
- Es braucht eine gültige `composer.lock`. Falls sie fehlt, vorher einmal `composer install` bzw. `composer update` im Projekt ausführen.
- Frontend-Änderungen sind erst nach `npm run build` bzw. `npm run dev` sichtbar.

## Beispiel

Ein Entwickler übernimmt ein fremdes Laravel-Projekt: Er öffnet den Composer-Graphen, schaltet Dev aus und sieht sofort, welche Pakete in Produktion landen, unter welchen Lizenzen — und über Freshness, welche Abhängigkeit seit Jahren nicht mehr aktualisiert wurde.

## Technische Notizen

- Guides werden per `import.meta.glob("../docs/*.md")` geladen; gültige Slugs kommen aus `config/docs.php`. Neuer Slug `composer` daher dort registriert.
- App-Seite (Tauri): Graph wird aus der geparsten `composer.lock` gerendert (Prod aus `require`, Dev aus `require-dev`).