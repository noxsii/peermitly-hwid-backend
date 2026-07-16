# Alles kostenlos, Auto-Update als einziges Pro-Feature, Landing-Darkmode

## Datum

2026-07-16

## Bereich

Landingpage, Dokumentation, Preise

## Kurzbeschreibung

Alle bisherigen Pro-Funktionen sind jetzt kostenlos. Einziges Pro-Feature bleibt das
automatische Update der App. Auf der Landingpage wurden die Preise entfernt (alles ist
kostenlos) und ein Dark-Mode-Umschalter ergänzt.

## Was ist neu?

- **Landing: Dark-Mode-Umschalter** — in der Navigation gibt es jetzt einen Sonne/Mond-
  Button. Besucher können zwischen Hell und Dunkel wechseln.
- **Landing: keine Preise mehr** — der Preis-Abschnitt zeigt statt „€4.99 / Testphase"
  klar „Everything's free / forever, for everyone". Der durchgestrichene Preis und die
  Testphasen-/Beta-Formulierung sind entfernt. Nav- und Footer-Link „Pricing" heißen
  jetzt „Free".
- **Docs: Pro-Kennzeichnung angepasst** — Mail, Nuxt, Astro, Next.js, MongoDB, Debug,
  Profiler, Composer-Graph und Sidebar-Editor sind nicht mehr als „Pro" markiert; ihre
  Hinweise sagen jetzt „Free for everyone".
- **Docs: neue Seite „Auto-update"** — beschreibt das automatische Update als einziges
  Pro-Feature und erklärt, dass ohne Pro einfach manuell aktualisiert wird.

## Warum wurde das geändert?

Alle Funktionen wurden freigeschaltet. Nur das automatische Update bleibt Pro. Die
Außendarstellung (Landing + Docs) muss das sauber widerspiegeln.

## Wie funktioniert es?

- **Dark Mode:** Button in der Landing-Navigation klicken — die Seite wechselt sofort
  zwischen Hell und Dunkel. Die Wahl wird im Browser gespeichert.
- **Preise:** Der „Free"-Abschnitt nennt keine Beträge mehr und listet alle enthaltenen
  Funktionen. Call-to-Action bleibt „Create your free account".
- **Auto-update in den Docs:** Unter Setup → „Auto-update" ist beschrieben, wie sich die
  App selbst aktualisiert (Pro) und wie man ohne Pro manuell aktualisiert.

## Betroffene Bereiche

- Landing: Navigation, Preis-Abschnitt, Footer
- Dokumentation: `config/docs.php`, 9 Feature-Seiten, neue Seite `auto-update`, `setup`
- SEO: `sitemap.xml` (Eintrag für `/guide/auto-update`)

## Wichtige Hinweise

- Es gibt aktuell **keine technische Sperre** für Pro — auch das Auto-Update ist im Code
  noch nicht plan-abhängig eingeschränkt. Diese Änderung betrifft nur Docs und Landing.
  Eine echte Absicherung des Update-Endpunkts müsste separat ergänzt werden.
- Das automatische Update selbst läuft in der Desktop-App (Tauri), nicht in diesem
  Backend.