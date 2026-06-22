# Landingpage hinzugefügt

## Datum

2026-05-21

## Bereich

Öffentlicher Bereich, Routing, Onboarding

## Kurzbeschreibung

Die Startseite (`/`) ist nicht mehr leer, sondern zeigt jetzt eine schlanke
Marketing-Landingpage für Permitly. Die Seite ist für alle erreichbar — auch
für bereits angemeldete Nutzer.

## Was ist neu?

- Neue Landingpage unter `/` mit Hero-Bereich, drei Feature-Kacheln, Call-to-Action
  und Footer.
- Anmelde-Button (`Sign in`) in der Top-Navigation, im Hero und im
  Abschluss-Call-to-Action. Alle Buttons führen zur bestehenden
  Anmeldeseite `/login`.
- Texte komplett auf Englisch (siehe Frontend-Sprachregel des Projekts).
- Helle, klare Optik (kein Dark Mode auf der Landingpage).

## Warum wurde das geändert?

Vor der Änderung lieferte `/` lediglich die leere Inertia-Hülle (`view('app')`)
ohne Inhalt. Wer Permitly zum ersten Mal aufrief, sah nichts, was beschreibt,
was das Produkt ist oder wo man sich anmeldet. Die neue Landingpage erklärt
das Produkt in wenigen Sekunden und bietet einen klaren Weg in den Login.

## Wie funktioniert es?

1. Aufruf von `https://permitly.test/` → es erscheint die neue
   Landingpage, unabhängig davon, ob ein Nutzer angemeldet ist.
2. Klick auf einen der **Sign in**-Buttons → Weiterleitung zur bekannten
   Login-Seite `/login`. Wer bereits eingeloggt ist, landet darüber direkt
   auf dem Dashboard (Login-Seite leitet eingeloggte Nutzer dorthin weiter).

Eine Registrierung gibt es bewusst nicht: Permitly arbeitet einladungs- bzw.
lizenzkey-basiert. Nirgends auf der Seite wird ein "Sign up" oder "Register"
angeboten.

## Betroffene Bereiche

- Route `GET /` (jetzt benannt `home`)
- Neuer Controller `App\Http\Controllers\LandingController`
- Neue Inertia-Seite `resources/js/pages/landing/Index.vue`
- Neue Komponenten unter `resources/js/components/landing/`
  (`LandingNav.vue`, `LandingHero.vue`, `LandingFeatures.vue`,
  `LandingCta.vue`, `LandingFooter.vue`)
- Tests unter `tests/Feature/Landing/LandingPageTest.php`

## Wichtige Hinweise

- Die Landingpage läuft bewusst nur im hellen Farbschema. Das System-Dark-Mode
  greift hier nicht, damit die Außenwirkung konsistent bleibt.
- Die Footer-Links (Documentation, Status, Privacy) sind aktuell Platzhalter
  (`href="#"`). Sie können später auf echte Ziele gemappt werden.
- Es wurden keine neuen Pakete installiert. Es werden ausschließlich bereits
  vorhandene Bausteine genutzt (shadcn-vue Button, Lucide Icons, Tailwind v4
  Design-Tokens).

## Beispiel

Erstkontakt mit Permitly:

> Ein Interessent öffnet `https://permitly.test/`. Er sieht in wenigen Worten,
> dass Permitly Lizenzkeys, HWID-Bindung und eine Aktivierungs-API anbietet,
> und klickt auf **Sign in →** in der Hero-Sektion. Er landet sofort auf
> `/login` und kann sich anmelden.

## Technische Notizen

- Routing: `routes/web.php` ruft `LandingController@show` auf. Der Controller
  rendert immer die Inertia-Seite `landing/Index` — egal, ob ein Nutzer
  eingeloggt ist oder nicht.
- Pest-Feature-Tests decken alle vier Fälle ab:
  Gast bekommt 200, Inertia-Komponente `landing/Index`, eingeloggter Nutzer
  sieht ebenfalls die Landingpage, Routenname ist `home`.
- Komponenten sind kleine, einzweck-orientierte SFCs ohne Props. Die Liste
  der Features wird als typisiertes `LandingFeature[]` (siehe
  `resources/js/types/landing.ts`) in `LandingFeatures.vue` gepflegt.
