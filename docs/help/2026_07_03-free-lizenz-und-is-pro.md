# FREE-Lizenz und is_pro-Feld

## Datum

2026-07-03

## Bereich

Abonnement, API, Dashboard, Admin

## Kurzbeschreibung

Es gibt jetzt einen neuen Plan **Free**. Er läuft nie ab. Zusätzlich liefert die API pro Abo das neue Feld **is_pro** — es ist nur bei kostenpflichtigen Plänen `true`.

## Was ist neu?

- Neuer Plan **Free** (`free`), ohne Ablaufdatum — wie Lifetime.
- Neues API-Feld **is_pro**: `false` bei Free, `true` bei allen anderen Plänen.
- Neues API-Feld **is_free** zur Klarstellung.
- Free kann im Admin-Panel wie jeder andere Plan vergeben werden.
- Auf dem Dashboard zeigt ein Free-Abo „Never expires" statt eines Ablaufdatums.

## Warum wurde das geändert?

Es sollte eine dauerhafte, kostenlose Lizenz geben. Die App muss zuverlässig erkennen, ob ein Nutzer einen Pro-Plan hat — dafür sorgt `is_pro`.

## Wie funktioniert es?

- Im Admin-Panel unter **Abonnements** beim Anlegen als Plan **Free** wählen.
- Der API-Endpunkt `/api/subscription` gibt nun zusätzlich `is_pro`, `is_free`, `is_lifetime` und `is_trial` zurück.
- Bei Free und Lifetime ist `days_remaining` gleich `null` (kein Ablauf).

## Betroffene Bereiche

- API: `GET /api/subscription`
- Dashboard-Karte „Subscription status"
- Admin-Panel: Abonnement anlegen / filtern

## Wichtige Hinweise

- Free zählt als aktives Abo (voller Zugriff), ist aber **kein** Pro.
- `is_pro = false` gilt ausschließlich für Free.

## Technische Notizen

- Enum `SubscriptionPlan`: neuer Case `FREE`, Helfer `isFree()`, `isPro()`, `isPerpetual()`.
- `SubscriptionResource` und der Inertia-Share liefern `is_pro` / `is_free`; `days_remaining` nutzt `isPerpetual()`.
- Frontend: `DashboardSubscription`-Type um `is_free` / `is_pro` erweitert.
