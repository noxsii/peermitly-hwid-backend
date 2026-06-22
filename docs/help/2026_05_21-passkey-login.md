# Passkey-Login und Passkey-Verwaltung

## Datum

2026-05-21

## Bereich

Authentifizierung, Settings, Benutzerverwaltung

## Kurzbeschreibung

Benutzer können sich nun per Passkey (WebAuthn) anmelden und ihre Passkeys in den Einstellungen verwalten. Klassisches E-Mail/Passwort-Login bleibt weiterhin verfügbar.

## Was ist neu?

- Neue Schaltfläche „Sign in with passkey" auf der Login-Seite.
- Neuer Bereich „Passkeys" auf der Settings-Seite zum Anlegen und Löschen von Passkeys.
- Passkeys werden in einer eigenen Tabelle gespeichert (`passkeys`) und sind an den Benutzer gebunden.
- Inaktive Benutzer können sich nicht per Passkey anmelden.

## Warum wurde das geändert?

Passkeys sind schneller, phishing-resistent und ersetzen das Passwort durch geräte- oder plattformbasierte Authentifizierung (Touch ID, Face ID, Windows Hello, Sicherheitsschlüssel). Damit wird Login einfacher und sicherer.

## Wie funktioniert es?

### Passkey einrichten

1. In den Einstellungen die Karte „Passkeys" öffnen.
2. Einen Namen für den Passkey eingeben (z. B. „MacBook Touch ID").
3. Auf „Register passkey" klicken.
4. Vom Browser/Betriebssystem den Passkey erstellen lassen (z. B. per Fingerabdruck oder Sicherheitsschlüssel).
5. Der neue Passkey erscheint in der Liste mit Erstellungsdatum.

### Mit Passkey anmelden

1. Auf der Login-Seite auf „Sign in with passkey" klicken.
2. Den vom Browser angebotenen Passkey auswählen und bestätigen.
3. Nach erfolgreicher Authentifizierung wird automatisch auf das Dashboard weitergeleitet.

### Passkey löschen

1. In der Passkeys-Karte den Mülleimer-Button neben dem entsprechenden Passkey klicken.
2. Den Lösch-Dialog bestätigen.
3. Der Passkey ist entfernt und kann nicht mehr zum Anmelden verwendet werden.

## Betroffene Bereiche

- Login-Seite (`/login`)
- Settings-Seite (`/settings`)
- Datenbank-Tabelle `passkeys`
- Routen: `/auth/passkey/options`, `/auth/passkey/verify`, `/settings/passkeys`

## Wichtige Hinweise

- Der Browser muss WebAuthn unterstützen (alle aktuellen Browser auf Desktop und Mobile).
- Wird WebAuthn nicht unterstützt, ist die Passkey-Schaltfläche nicht sichtbar.
- Ein Passkey ist immer fest an genau ein Konto gebunden.
- Inaktive Benutzer (`is_active = false`) werden trotz gültigem Passkey abgelehnt.
- Beim Löschen erfolgt keine Bestätigung per E-Mail.

## Beispiel

Ein Mitarbeiter richtet auf seinem Notebook per Touch ID einen Passkey ein. Beim nächsten Login klickt er auf „Sign in with passkey", legt den Finger auf den Sensor und ist sofort angemeldet — ohne Passwort.

## Technische Notizen

- Verwendet `spatie/laravel-passkeys` (v1.8) auf Backend-Seite.
- Frontend nutzt `@simplewebauthn/browser` (v13).
- Registrierungs- und Login-Optionen werden in der Session zwischengespeichert.
- Host wird aus `config('app.url')` abgeleitet.
- Authentifizierungs-Action ruft `Auth::login($user, remember: true)` und regeneriert die Session.
