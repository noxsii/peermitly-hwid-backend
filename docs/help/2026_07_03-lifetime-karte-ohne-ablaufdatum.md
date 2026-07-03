# Lifetime-Abo zeigt kein Ablaufdatum mehr

## Datum

2026-07-03

## Bereich

Dashboard, Abonnement

## Kurzbeschreibung

Die Abo-Karte auf dem Dashboard zeigt bei einem **Lifetime**-Abo jetzt „Never expires" statt eines unsinnigen Ablaufdatums.

## Was ist neu?

- Bei Lifetime-Abos wird **kein** „… days left" und **kein** „Valid until …" mehr angezeigt.
- Stattdessen steht dort **„Never expires"**.
- Für alle anderen Pläne (Trial, Tag, Woche, Monat) bleibt die Anzeige mit Resttagen und Ablaufdatum unverändert.

## Warum wurde das geändert?

Ein Lifetime-Abo läuft nicht ab. Die Karte zeigte technisch bedingt „36525 days left" und „Valid until 4. Juli 2126" — das war verwirrend und ergab keinen Sinn.

## Betroffene Bereiche

- Dashboard nach dem Login (Karte „Subscription status")

## Wichtige Hinweise

- Rein visuell — an Laufzeit oder Zugriff ändert sich nichts.

## Technische Notizen

- Neues Feld `is_lifetime` im Inertia-Subscription-Payload (`HandleInertiaRequests`).
- `Dashboard.vue` blendet Resttage und Ablaufdatum bei `is_lifetime` aus.
