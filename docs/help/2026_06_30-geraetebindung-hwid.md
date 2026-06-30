# Gerätebindung über Hardware-ID (HWID)

## Datum

2026-06-30

## Bereich

Anmeldung, Benutzer, API, Filament

## Kurzbeschreibung

Ein Konto kann jetzt nur noch auf einem einzigen Gerät genutzt werden. Beim ersten Login wird das Konto fest an die Hardware-ID (HWID) dieses Geräts gebunden. Anmeldungen von einem anderen Gerät werden abgelehnt. Das verhindert, dass Nutzer ihren Account weitergeben und auf mehreren Rechnern gleichzeitig verwenden.

## Was ist neu?

- Die App (Windows/Desktop) sendet beim Login zusätzlich eine eindeutige Hardware-ID (`hwid`).
- Der erste erfolgreiche Login bindet das Konto an diese HWID. Sie wird am Benutzer gespeichert.
- Jeder weitere Login von einem anderen Gerät wird mit "This account is locked to another device." abgelehnt.
- Bei jedem Login werden alle vorherigen Sitzungen (Tokens) gelöscht. Es gibt also immer nur eine aktive Sitzung.
- Jede angemeldete Anfrage muss die HWID im Header `X-HWID` mitschicken. Stimmt sie nicht mit der gebundenen HWID überein, wird die Anfrage mit "Device mismatch." abgelehnt. So lässt sich ein kopiertes Token nicht auf einem anderen Rechner verwenden.
- Im Filament-Adminbereich wird die gebundene HWID beim Benutzer angezeigt und kann dort zurückgesetzt werden.

## Warum wurde das geändert?

Bisher konnte sich ein Konto beliebig oft und auf beliebig vielen Geräten anmelden. Dadurch war Account-Sharing problemlos möglich. Die Gerätebindung stellt sicher, dass ein Abo nur von einer Person auf einem Gerät genutzt wird.

## Wie funktioniert es?

1. Beim ersten Login sendet die App die HWID mit.
2. Das System prüft: Ist am Konto noch keine HWID gespeichert? Dann wird die gesendete HWID übernommen (Bindung).
3. Ist bereits eine HWID gespeichert und stimmt mit der gesendeten überein? Login erlaubt, alte Sitzung wird beendet.
4. Stimmt die HWID nicht überein? Login abgelehnt (Fehler 403).
5. Bei jeder weiteren Aktion in der App wird die HWID im Header geprüft.

## Betroffene Bereiche

- API-Login (`POST /api/login`)
- Alle angemeldeten API-Routen (`/api/user`, `/api/subscription`)
- Benutzer-Datenstruktur (neues Feld `hwid`)
- Filament: Benutzer-Formular und Benutzer-Tabelle

## Wichtige Hinweise

- **Geräte­wechsel:** Wenn ein Nutzer ein neues Gerät bekommt, muss ein Administrator die HWID am Benutzer zurücksetzen. Dazu im Filament-Adminbereich den Benutzer öffnen, das Feld "Gerät (HWID)" leeren und speichern. Beim nächsten Login wird das Konto neu an das neue Gerät gebunden.
- Die HWID wird als Hash gesendet, nicht als Klartext-Seriennummer.
- Die App muss bei jeder Anfrage den Header `X-HWID` setzen, sonst werden angemeldete Anfragen mit gebundener HWID abgelehnt.

## Beispiel

Ein Nutzer meldet sich zu Hause am PC an → Konto wird an diesen PC gebunden. Gibt er seine Zugangsdaten an einen Freund weiter, kann sich dieser auf seinem eigenen Rechner nicht anmelden – der Login wird abgelehnt, weil die HWID nicht passt.

## Technische Notizen

- Neues Feld `users.hwid` (nullable string).
- Bindungs- und Sitzungslogik in `AuthenticateApiUserAction`.
- Header-Prüfung in der Middleware `EnsureMatchingHwid` (Alias `hwid`), aktiv auf der `auth:sanctum`-Routengruppe.
- Vergleich erfolgt mit `hash_equals` (timing-sicher).
