# Abgelaufene Abonnements fallen auf Free zurück

## Datum

2026-07-06

## Bereich

Abonnements, Benutzer

## Kurzbeschreibung

Wenn ein Abonnement abläuft, wird der Benutzer nicht mehr ohne Abo zurückgelassen (was zur Deaktivierung führte), sondern erhält automatisch ein Free-Abonnement.

## Was ist neu?

Der tägliche Ablauf-Job markiert ein abgelaufenes Abonnement weiterhin als „Expired". Neu: Direkt danach wird geprüft, ob der Benutzer noch ein anderes aktives Abonnement hat. Falls nicht, wird automatisch ein Free-Abonnement angelegt.

## Warum wurde das geändert?

Bisher stand ein Benutzer nach Ablauf seines Abos ohne aktives Abonnement da und wurde vom Enforcement-Job (`users:enforce-subscription`) auf inaktiv gesetzt. Gewünscht ist stattdessen ein Downgrade auf den Free-Plan, damit der Account nutzbar bleibt.

## Wie funktioniert es?

1. Der geplante Befehl `subscriptions:expire` läuft wie bisher und verteilt pro Abonnement einen Job.
2. Der Job prüft: Abo aktiv und Laufzeit abgelaufen? Wenn ja, Status wird auf „Expired" gesetzt.
3. Neu: Hat der Benutzer danach kein anderes aktives Abonnement, wird sofort ein Free-Abonnement mit Status „Active" angelegt.
4. Hat der Benutzer bereits ein anderes laufendes Abo (z. B. schon verlängert), wird kein Free-Abo angelegt.

## Betroffene Bereiche

- Abonnement-Ablauf (`subscriptions:expire`, `ExpireSubscriptionAction`)
- Benutzer-Deaktivierung (`users:enforce-subscription`) — greift bei abgelaufenen Abos jetzt nicht mehr, da immer ein aktives Free-Abo existiert

## Wichtige Hinweise

- Free- und Lifetime-Abos laufen praktisch nie ab (Enddatum ca. 100 Jahre in der Zukunft) und sind daher von diesem Ablauf nicht betroffen.
- Bereits vor dieser Änderung abgelaufene Abos werden nicht rückwirkend auf Free umgestellt; das betrifft nur zukünftige Abläufe.

## Beispiel

Ein Benutzer hat ein Monats-Abo bis 05.07.2026. Am 06.07.2026 markiert der Job das Abo als abgelaufen und legt automatisch ein Free-Abo an. Der Benutzer bleibt aktiv und behält Zugriff auf die Free-Funktionen.

## Technische Notizen

- `ExpireSubscriptionAction` injiziert jetzt `GrantSubscriptionAction` und ruft es mit `SubscriptionPlan::FREE` auf, wenn `Subscription::query()->whereActive()->whereBelongsTo($user)` leer ist.
- Tests: `tests/Feature/Subscriptions/ExpireSubscriptionActionTest.php` (Free-Fallback + kein Doppel-Grant bei laufendem Zweit-Abo).