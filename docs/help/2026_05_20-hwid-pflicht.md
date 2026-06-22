# HWID-Pflicht pro Lizenzkey

## Datum

2026-05-20

## Bereich

Lizenzkeys, API

## Kurzbeschreibung

Jeder Lizenzkey kann optional auf eine Hardware-ID (HWID) gebunden werden.
Ist die Pflicht aktiv, muss die externe Software bei jedem API-Check eine
HWID mitliefern.

## Was ist neu?

- Schalter **Require Hardware ID (HWID)** im "New License Key"-Dialog und im
  Edit-Flow.
- Neue API-Antwort-Status `hwid_required`, `hwid_mismatch`,
  `activation_limit_reached`.
- Neue Tabelle `license_key_activations` zum Speichern der Maschinen pro
  Key.

## Warum wurde das geändert?

Bei wertvollen oder pro-Gerät verkauften Lizenzen darf ein Key nicht
beliebig weitergegeben werden. Mit der HWID-Bindung wird der Key auf
konkrete Hardware-Identifier gebunden.

## Wie funktioniert es?

1. Beim Erstellen eines Keys aktiviert der Admin **Require HWID**.
2. Optional setzt er **Maximum activations** (z.B. 1 = Single-Gerät).
3. Externe Software ruft `POST /api/license-keys/check` mit zusätzlichem
   Feld `hwid` auf:
   ```json
   { "key": "LIC-...", "product": "office-efficient", "hwid": "device-abc" }
   ```
4. Erster Check mit HWID → Aktivierung wird registriert, Status `active`.
5. Folgende Checks mit derselben HWID → OK, `last_seen_at` wird aktualisiert.
6. Check mit neuer HWID:
   - Wenn `max_activations` noch nicht erreicht: neue Aktivierung anlegen,
     OK.
   - Wenn erreicht: Antwort `activation_limit_reached`.
7. Check ohne HWID auf einen HWID-pflichtigen Key → Antwort
   `hwid_required` (HTTP 422). Der Key wird **nicht** aktiviert.

## Betroffene Bereiche

- Tabelle `license_keys`, Spalte `requires_hwid_check`.
- Tabelle `license_key_activations`.
- `CheckLicenseKeyAction` — HWID-Logik im Kern-Flow.
- API-Antwort-Schemas.

## Wichtige Hinweise

- Ohne HWID-Pflicht (`requires_hwid_check = false`) ist die HWID im
  Request rein optional und wird nur geloggt — keine harte Prüfung.
- `max_activations = 1` + `requires_hwid_check = true` ergibt eine
  Single-HWID-Lock.
- Bei Geräteswechsel des Kunden: Admin muss bestehende Aktivierungen
  löschen (in V1 manuell über die DB) oder den Key über die Admin-UI
  ersetzen.

## Beispiel

Single-Device-License:

```
Typ: Standard License
Produkt: OfficeEfficient
Require HWID: an
Maximum activations: 1
```

Software auf Gerät A → erste Aktivierung gelingt.
Software auf Gerät B → Antwort:

```json
{
  "valid": false,
  "status": "activation_limit_reached",
  "message": "Activation limit reached for this license key."
}
```

## Technische Notizen

- Aktivierungs-Eintrag enthält `machine_id`, `hostname`, `ip_address`,
  `user_agent`, `activated_at`, `last_seen_at`.
- Unique-Index auf `(license_key_id, machine_id)` verhindert Duplikate.
