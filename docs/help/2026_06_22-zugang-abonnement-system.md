# Zugang über Abonnements

## Datum

2026-06-22

## Bereich

Zugang, Benutzer, Filament Admin

## Kurzbeschreibung

Es gibt jetzt ein System, mit dem festgelegt wird, ob ein Benutzer Zugang zur Software hat. Der Zugang wird als Abonnement gespeichert (Tag, Woche oder Monat). Aktuell wird der Zugang manuell im Admin-Panel vergeben. Später übernimmt das die Zahlung über Stripe.

## Was ist neu?

- Neue Tabelle `subscriptions`, die pro Benutzer speichert, welchen Zugang er hat.
- Drei Laufzeiten: **Day pass** (1 Tag), **Weekly** (7 Tage), **Monthly** (30 Tage).
- Jeder Eintrag hat einen Status: **Active**, **Expired** oder **Canceled**.
- Im Admin-Panel gibt es unter „Access" → „Subscriptions" eine Liste, in der Zugänge angelegt, bearbeitet und gelöscht werden können.
- Es gibt eine technische Prüfung (Middleware `subscribed`), mit der geschützte Bereiche nur für Benutzer mit aktivem Zugang freigegeben werden.

## Warum wurde das geändert?

Wir brauchen eine saubere Stelle, an der hinterlegt ist, wer Zugang hat und wie lange. Das muss sofort manuell über das Panel funktionieren, später aber auch automatisch über die Zahlung mit Stripe befüllt werden können. Die Tabelle ist dafür schon vorbereitet (Felder für Stripe sind vorhanden, aber noch leer).

## Wie funktioniert es?

1. Im Admin-Panel „Subscriptions" → „Create" öffnen.
2. Benutzer auswählen, Plan wählen (Tag, Woche oder Monat).
3. Startzeitpunkt steht standardmäßig auf „jetzt". Das Enddatum wird automatisch aus dem Plan berechnet (z. B. Monat = Start + 30 Tage). Ein manuelles Enddatum kann optional gesetzt werden.
4. Speichern. Der Benutzer hat damit einen aktiven Zugang bis zum Enddatum.

Ein Zugang gilt als **aktiv**, wenn der Status „Active" ist **und** das Enddatum noch in der Zukunft liegt. Hat ein Benutzer mehrere Einträge, zählt der mit dem am weitesten in der Zukunft liegenden Enddatum.

## Betroffene Bereiche

- Admin-Panel: neue Navigationsgruppe „Access" mit „Subscriptions".
- Datenbank: neue Tabelle `subscriptions`.
- Geschützte Bereiche: können mit der Middleware `subscribed` abgesichert werden.

## Wichtige Hinweise

- Das Login funktioniert weiterhin auch für Benutzer **ohne** aktiven Zugang. Der Zugang regelt nur, ob geschützte Funktionen genutzt werden dürfen, nicht ob man sich einloggen kann.
- Die Zahlung mit Stripe ist noch nicht angebunden. Zugänge werden bis dahin nur manuell im Panel gesetzt.
- Der API-Endpunkt für den Login aus der Windows-App kommt später; die Prüfung auf aktiven Zugang ist dafür schon vorbereitet.

## Beispiel

Ein Kunde kauft (vorerst manuell freigegeben) einen Wochen-Zugang am 22.06.2026. Im Panel wird „Weekly" gewählt, Start „jetzt". Das System setzt das Enddatum automatisch auf den 29.06.2026. Bis dahin hat der Kunde Zugang, danach läuft der Zugang automatisch aus.

## Technische Notizen

- Enums: `App\Enums\SubscriptionPlan`, `App\Enums\SubscriptionStatus`.
- Modell `App\Models\Subscription` mit eigenem Query-Builder `SubscriptionBuilder` (`whereActive()`).
- Zugang vergeben über `App\Actions\Subscriptions\GrantSubscriptionAction`.
- Prüfung über die Relation `User::activeSubscription()` bzw. die Middleware-Alias `subscribed` (`App\Http\Middleware\EnsureActiveSubscription`).
- Felder `stripe_subscription_id` und `stripe_customer_id` sind für die spätere Stripe-Anbindung reserviert.
- Ablauf abgelaufener Zugänge: Der Befehl `subscriptions:expire` läuft stündlich (geplant in `routes/console.php`). Er holt alle Abonnements und feuert pro Abonnement einen Job `App\Jobs\ExpireSubscriptionJob`. Der Job ruft `App\Actions\Subscriptions\ExpireSubscriptionAction`, der ein einzelnes Abonnement nur dann auf „Expired" setzt, wenn es noch aktiv ist und die Laufzeit vorbei ist.
