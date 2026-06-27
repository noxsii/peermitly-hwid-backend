# Neuer API-Endpunkt: Subscription-Status prüfen

## Datum

2026-06-27

## Bereich

API, Abonnements

## Kurzbeschreibung

Es gibt einen neuen API-Endpunkt, mit dem die App regelmäßig prüfen kann, ob das Abonnement eines Nutzers noch gültig ist. Die App ruft ihn etwa alle 10 Minuten auf.

## Was ist neu?

- Neuer Endpunkt `GET /api/subscription`
- Liefert zurück, ob der Zugang aktuell gültig ist, und alle wichtigen Abo-Daten

## Warum wurde das geändert?

Die App muss laufend wissen, ob das Abonnement noch aktiv ist, ohne den Nutzer erneut anmelden zu müssen. So kann sie den Zugang sperren, sobald das Abo abgelaufen oder der Account deaktiviert ist.

## Wie funktioniert es?

1. Die App sendet eine `GET`-Anfrage an `/api/subscription`.
2. Authentifizierung über den beim Login erhaltenen Token (Bearer-Token, `auth:sanctum`).
3. Der Server prüft, ob der Account aktiv ist und ein gültiges (aktives, nicht abgelaufenes) Abonnement vorliegt.
4. Die Antwort enthält das Ergebnis und die Abo-Daten.

## Antwort

Gültiges Abo:

```json
{
  "valid": true,
  "is_active": true,
  "subscription": {
    "plan": "week",
    "plan_label": "Weekly",
    "status": "active",
    "starts_at": "2026-06-27T10:00:00+00:00",
    "ends_at": "2026-07-04T10:00:00+00:00",
    "days_remaining": 7
  }
}
```

Kein gültiges Abo oder Account deaktiviert:

```json
{
  "valid": false,
  "is_active": true,
  "subscription": null
}
```

## Betroffene Bereiche

- API-Routen (`routes/api.php`)
- Neuer Controller `Api\SubscriptionController`

## Wichtige Hinweise

- `valid` ist nur dann `true`, wenn der Account aktiv ist UND ein aktives, nicht abgelaufenes Abonnement existiert.
- `days_remaining` zählt volle Kalendertage bis zum Ablauf und ist nie kleiner als 0.
- Ohne gültigen Token antwortet der Endpunkt mit `401 Unauthorized`.
- Der Endpunkt verändert keine Daten — reiner Lesezugriff.

## Technische Notizen

- Routenname: `api.subscription.status`.
- Nutzt die bestehende `activeSubscription`-Beziehung des Nutzers (aktiv + nicht abgelaufen, längste Laufzeit) und die `SubscriptionResource`.
