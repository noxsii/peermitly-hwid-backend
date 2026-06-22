# Lizenzkey-System

## Datum

2026-05-20

## Bereich

Lizenzkeys, Lizenzkey-Typen, Sidebar, API

## Kurzbeschreibung

Komplettes Lizenz-System mit Adminoberfläche zum Erstellen und Verwalten von
Lizenzkeys, einer öffentlichen API zum Prüfen der Keys durch externe Software
und einem Aktivierungs-Konzept, bei dem die Laufzeit erst beim ersten
erfolgreichen API-Abruf startet.

## Was ist neu?

- Neuer Menüpunkt **License Keys** in der Seitenleiste.
- Verwaltung von **Lizenzkey-Typen** (Random/UUID/Pattern Generator) inklusive
  Live-Vorschau der erzeugten Beispielkeys.
- Erstellung einzelner Lizenzkeys und Bulk-Erstellung (mehrere Keys auf einmal).
- HWID-Schalter pro Key: optional Hardware-ID-Bindung über die API.
- API-Endpoint `POST /api/license-keys/check` zur Prüfung durch externe
  Software (geschützt über Sanctum-Tokens).
- Statussystem `pending → active → expired/revoked/blocked`.
- Revoke, Restore, Extend pro Lizenzkey direkt in der Admin-Oberfläche.
- CSV-Export aller Lizenzkeys eines Teams.

## Warum wurde das geändert?

Permitly braucht ein eigenes Lizenzsystem, um eigene Produkte vor unauthorized
Nutzung zu schützen und Lizenzlaufzeiten kontrolliert zu starten. Die
Laufzeit-Logik ist bewusst aktivierungsbasiert: Ein vor Monaten erstellter
Key verliert keine Tage, weil der Kunde ihn noch nicht eingespielt hat.

## Wie funktioniert es?

1. Admin öffnet **License Keys** in der Sidebar.
2. Über **Manage types** legt der Admin einmalig die gewünschten Key-Formate
   an (z.B. Standard 12-stellig, Trial 8-stellig, Enterprise 16-stellig,
   UUID-basiert).
3. Über **New key** öffnet sich ein Dialog. Admin wählt Typ + Produkt +
   optional Kunde, setzt Gültigkeit (z.B. 12 Monate) und entscheidet, ob
   HWID-Pflicht aktiv sein soll.
4. Der Key wird mit Status **pending** angelegt. Laufzeit läuft noch nicht.
5. Externe Software ruft `POST /api/license-keys/check` mit dem Key auf.
6. Beim ersten erfolgreichen Check setzt das System `activated_at = now()`
   und berechnet `expires_at`. Status springt auf **active**.
7. Folgende Checks verändern die Laufzeit nicht mehr — sie geben nur den
   aktuellen Status zurück.

## Betroffene Bereiche

- Neue Sidebar-Ebene **License Keys** + `/license-keys` und
  `/license-keys/types`.
- API-Routen unter `/api/license-keys/*` und `/api/license-key-types/*`.
- Einstellungen: neue Karte **API Tokens** zur Erzeugung externer
  Zugriffstoken.
- Datenbank: neue Tabellen `products`, `customers`, `license_key_types`,
  `license_keys`, `license_key_checks`, `license_key_activations`.

## Wichtige Hinweise

- Die Laufzeit eines Keys läuft **nicht** ab Erstellung, sondern erst ab
  erstem erfolgreichem API-Abruf.
- Lifetime-Keys haben `expires_at = null` und laufen nie ab.
- Revoke ist sofort wirksam: Folge-Checks geben `valid: false, status: revoked`
  zurück.
- HWID-Pflicht (`requires_hwid_check = true`) lässt sich pro Key
  unabhängig vom Typ schalten. Ohne HWID im Request → `hwid_required`.
- Pro Team werden die Daten strikt getrennt (Multi-Tenancy via `team_id`).

## Beispiel

Standard-Workflow für einen Software-Verkauf:

1. Admin erstellt Lizenzkey-Typ "Standard License" einmalig.
2. Nach Bestellung erstellt Admin einen Key:
   ```
   Typ: Standard License
   Produkt: OfficeEfficient
   Kunde: max@example.com
   Gültigkeit: 12 Monate
   HWID-Pflicht: aus
   ```
3. System erzeugt `LIC-K7DM-Q9RA-X4TP`, Status `pending`.
4. Kunde gibt Key in Software ein.
5. Software ruft `POST /api/license-keys/check` mit Bearer-Token und
   `{ "key": "LIC-K7DM-Q9RA-X4TP", "product": "office-efficient" }`.
6. Antwort:
   ```json
   {
     "valid": true,
     "status": "valid",
     "first_activation": true,
     "expires_at": "2027-05-20T...",
     "days_remaining": 365,
     "lifetime": false
   }
   ```
7. Software speichert Aktivierung und verifiziert in Folge regelmäßig.

## Technische Notizen

- Race-Condition bei Erst-Aktivierung verhindert durch
  `DB::transaction(lockForUpdate())` in `ActivateLicenseKeyAction`.
- Key-Generierung über `GenerateLicenseKeyAction` mit Kollisions-Retry
  (max 10 Versuche, danach `LicenseKeyGenerationException`).
- Normalisierung über `NormalizeLicenseKeyAction`: entfernt Trennzeichen,
  Leerzeichen, Zero-Width-Chars; uppercase wenn nicht case-sensitive.
- Sanctum-Token-Abilities: `license-keys:check`, `license-keys:read`,
  `license-keys:manage`, `license-key-types:manage`.
- Rate-Limit Check-Endpoint: 60 Requests pro Minute pro User+IP.
- DTOs in `app/Data/LicenseKeys/` (Configuration, Context, Request, Result).
