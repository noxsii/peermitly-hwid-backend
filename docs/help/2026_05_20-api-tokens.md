# API-Token-Verwaltung

## Datum

2026-05-20

## Bereich

Einstellungen, API, Sicherheit

## Kurzbeschreibung

Auf der Einstellungs-Seite gibt es eine neue Karte **API Tokens**. Nutzer
erstellen dort persönliche API-Tokens, schränken die Berechtigungen
(Abilities) ein und sperren Tokens bei Bedarf wieder.

## Was ist neu?

- Karte **API Tokens** in `/settings`.
- Dialog "New API Token" zum Erstellen eines Tokens mit Name und
  Auswahl der Abilities.
- Plain-text Token wird **einmalig** in einem zweiten Dialog angezeigt
  (mit Copy-Button). Danach ist nur noch der gehashte Token in der
  Datenbank.
- Bestehende Tokens werden mit Name, Abilities und letzter Nutzung
  gelistet.
- Revoke per Confirm-Dialog.

## Warum wurde das geändert?

Externe Software (z.B. eigene Produkte, die Lizenzkeys gegen die API
prüfen) braucht Tokens zur Authentifizierung. Tokens sollten granular auf
einzelne Fähigkeiten beschränkbar sein, damit ein Token für die
Schlüsselprüfung nicht auch Lizenzkey-Typen anlegen oder löschen kann.

## Wie funktioniert es?

1. Nutzer öffnet **Settings**.
2. Klick auf "+" in der Karte **API Tokens** → Dialog öffnet sich.
3. Name vergeben (z.B. "OfficeEfficient production") und Abilities
   anhaken.
4. Submit → Backend erstellt Personal-Access-Token und gibt Plain-Token
   zurück.
5. Token-Anzeige-Dialog öffnet sich automatisch, Nutzer kopiert Token.
6. Nach Schließen ist Plain-Token verloren — nur noch der Token-Hash in
   der DB.

## Betroffene Bereiche

- Tabelle `personal_access_tokens` (durch Sanctum standardmäßig
  vorhanden).
- Routen `POST /settings/api-tokens` und
  `DELETE /settings/api-tokens/{id}`.
- API-Endpoints prüfen Token-Abilities via `ability:<scope>`-Middleware.

## Wichtige Hinweise

- Plain-Token nur einmal sichtbar. Geht verloren → neues Token erstellen.
- Abilities decken aktuell:
  - `license-keys:check` — externe Software, prüft Lizenzkeys
  - `license-keys:read` — lesender Zugriff auf Lizenzkeys
  - `license-keys:manage` — Vollzugriff: Erstellen, Sperren,
    Verlängern, Löschen
  - `license-key-types:manage` — Typen-Verwaltung
- Token gehört dem Nutzer, der ihn erstellt hat (über `tokenable_id`).
  Andere Nutzer können ihn nicht sehen oder löschen.
- Beim Revoke fliegt der externe Zugang sofort raus.

## Beispiel

Ein neuer Token für die externe Software wird angelegt:

```
Name: OfficeEfficient (production)
Abilities: license-keys:check
```

Plain-Token wird einmalig angezeigt:

```
permitly_pat_4xKL3pQRSt...wXYZ
```

Externe Software setzt Header:

```
Authorization: Bearer permitly_pat_4xKL3pQRSt...wXYZ
```

## Technische Notizen

- Backend nutzt `User::createToken(name, abilities)` von Sanctum.
- Frontend (`CreateApiTokenDialog.vue`) verwendet Inertia `useHttp`, nicht
  `useForm` — Backend gibt JSON zurück, kein Inertia-Redirect.
- Liste lädt sich nach erfolgreichem Create via
  `router.reload({ only: ['tokens'] })` neu.
