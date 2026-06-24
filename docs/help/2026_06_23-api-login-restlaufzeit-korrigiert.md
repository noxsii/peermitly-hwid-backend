# API-Login: Restlaufzeit der Subscription korrigiert

## Datum

2026-06-23

## Bereich

API, Login, Subscription

## Kurzbeschreibung

Der Login-Endpunkt der API (`POST /api/login`) hat die verbleibenden Tage einer Subscription (`days_remaining`) teilweise um einen Tag zu hoch angezeigt. Die Berechnung wurde korrigiert und entspricht jetzt der Anzeige auf dem Dashboard.

## Was ist neu?

- `days_remaining` im Login-Response zählt jetzt volle Kalendertage.
- Die Uhrzeit zum Zeitpunkt der Anfrage beeinflusst das Ergebnis nicht mehr.
- API und Dashboard liefern jetzt denselben Wert.

## Warum wurde das geändert?

Die alte Berechnung verglich den genauen aktuellen Zeitpunkt (mit Uhrzeit) mit dem Ablaufzeitpunkt und rundete immer auf. Dadurch wurde häufig ein Tag zu viel angezeigt. Dasselbe Problem war zuvor schon auf dem Dashboard aufgetreten und dort behoben worden, im API-Login aber übersehen.

## Wie funktioniert es?

Die Restlaufzeit wird nun von Tagesbeginn zu Tagesbeginn berechnet (Kalendertage) statt von Uhrzeit zu Uhrzeit.

Beispiel: Es ist der 23.06.2026 um 08:00 Uhr, die Subscription endet am 22.07.2026 um 09:00 Uhr.

- Vorher (falsch): 30 Tage
- Jetzt (richtig): 29 Tage

## Betroffene Bereiche

- Endpunkt `POST /api/login`, Feld `subscription.days_remaining`
- `App\Http\Resources\Api\SubscriptionResource`

## Wichtige Hinweise

- Die Felder `starts_at` und `ends_at` waren nie betroffen und bleiben unverändert.
- Clients, die sich auf `days_remaining` verlassen, erhalten jetzt den korrekten, mit dem Dashboard übereinstimmenden Wert.

## Technische Notizen

- Korrigiert in `SubscriptionResource::toArray()`:
  - vorher: `max(0, (int) ceil(now()->diffInDays($this->ends_at)))`
  - jetzt: `max(0, (int) round(today()->diffInDays($this->ends_at->copy()->startOfDay())))`
- Gleiche Logik wie `HandleInertiaRequests::subscriptionPayload()` (Dashboard).
- Hinweis: Die Berechnung ist an zwei Stellen dupliziert. Ein gemeinsamer Helper (z. B. am `Subscription`-Builder/DTO) würde künftige Abweichungen verhindern.
- Test: `tests/Feature/Api/ApiLoginTest.php` ("login days_remaining counts calendar days...").