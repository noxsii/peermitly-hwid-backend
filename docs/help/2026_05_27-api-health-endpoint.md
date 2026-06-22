# API Health Check Endpunkt

## Datum

2026-05-27

## Bereich

API, Monitoring, Status Page

## Kurzbeschreibung

Neuer öffentlicher API-Endpunkt `GET /api/health`, der den Zustand der wichtigsten Abhängigkeiten (Datenbank, Cache, Redis) prüft. Geeignet zur Einbindung in externe Status Pages oder Uptime-Monitore.

## Was ist neu?

- Neuer Endpunkt `GET /api/health`.
- Kein Login, kein API-Token, keine Rate Limits.
- Wird nicht in den API-Logs gespeichert.
- Antwortet mit JSON: Gesamtstatus, Zeitstempel und Details je Prüfung.

## Warum wurde das geändert?

Damit externe Monitoring-Systeme (z. B. UptimeRobot, BetterStack, eigene Status Page) den Zustand des Systems regelmäßig abfragen und Probleme automatisch erkennen können.

## Wie funktioniert es?

1. GET-Request auf `https://<host>/api/health` senden.
2. Antwort:
   - HTTP 200 wenn alles `ok` oder `degraded` ist.
   - HTTP 503 wenn mindestens eine Komponente `down` ist.
3. JSON-Body enthält drei Prüfungen: `database`, `cache`, `redis`.

### Antwort-Beispiel (ok)

```json
{
  "status": "ok",
  "checked_at": "2026-05-27T10:15:32+00:00",
  "checks": [
    { "name": "database", "status": "ok", "latency_ms": 3, "message": null },
    { "name": "cache",    "status": "ok", "latency_ms": 1, "message": null },
    { "name": "redis",    "status": "ok", "latency_ms": 1, "message": null }
  ]
}
```

### Antwort-Beispiel (Ausfall)

```json
{
  "status": "down",
  "checked_at": "2026-05-27T10:16:01+00:00",
  "checks": [
    { "name": "database", "status": "down", "latency_ms": 5000, "message": "connection refused" },
    { "name": "cache",    "status": "ok",   "latency_ms": 1,    "message": null },
    { "name": "redis",    "status": "ok",   "latency_ms": 1,    "message": null }
  ]
}
```

## Betroffene Bereiche

- API-Route `GET /api/health`
- Externes Monitoring / Status Page

## Wichtige Hinweise

- Der Endpunkt ist bewusst öffentlich; es werden keine sensiblen Daten zurückgegeben.
- Statusbedeutung:
  - `ok`: Komponente erreichbar und antwortet.
  - `degraded`: Komponente langsam oder eingeschränkt, aber funktioniert.
  - `down`: Komponente nicht erreichbar.
- HTTP 503 als Trigger für Alarmierung verwenden.

## Beispiel

Einbindung in einen externen Uptime-Monitor (z. B. UptimeRobot): URL `https://peermitly.test/api/health`, Methode `GET`, erwarteter Status-Code 200.

## Technische Notizen

- Aktion: `App\Actions\Health\CheckSystemHealthAction` (implementiert `App\Contracts\SystemHealthChecker`).
- Controller: `App\Http\Controllers\Api\Health\HealthCheckController`.
- DTO: `App\Data\Health\SystemHealthData`, `App\Data\Health\HealthCheckResult`.
- Enum: `App\Enums\HealthStatus` (`OK`, `DEGRADED`, `DOWN`).
- `LogApiRequest`-Middleware via `withoutMiddleware()` ausgeschlossen.
