# Login-Seite leitet eingeloggte Nutzer zum Dashboard

## Datum

2026-05-20

## Bereich

Authentifizierung, Login

## Kurzbeschreibung

Ruft ein bereits eingeloggter Nutzer die Login-Seite (`/login`) auf, wird er
automatisch zum Dashboard weitergeleitet.

## Was ist neu?

Die Login-Routen (`GET /login` und `POST /login`) sind jetzt durch die
`guest`-Middleware geschützt. Nur nicht authentifizierte Besucher sehen das
Login-Formular. Authentifizierte Nutzer werden direkt auf `/dashboard`
umgeleitet.

## Warum wurde das geändert?

Bisher konnten eingeloggte Nutzer die Login-Seite erneut aufrufen, was
verwirrend war und keinen Mehrwert bot. Die neue Weiterleitung führt direkt
zum produktiven Arbeitsbereich.

## Wie funktioniert es?

1. Nutzer ist eingeloggt und ruft `/login` auf.
2. Die `guest`-Middleware erkennt die aktive Session.
3. Laravel leitet automatisch auf die `dashboard`-Route weiter.
4. Nicht eingeloggte Nutzer sehen weiterhin das normale Login-Formular.

## Betroffene Bereiche

- `routes/web.php` (Login-Routen sind nun in einer `guest`-Middleware-Gruppe)
- Auth-Flow für `/login` (GET und POST)

## Wichtige Hinweise

- Die `intended()`-Weiterleitung nach erfolgreichem Login bleibt unverändert.
- Die Logout-Route ist nicht betroffen.

## Beispiel

Ein eingeloggter Nutzer klickt versehentlich auf einen alten Login-Link
oder gibt `/login` manuell ein und landet sofort auf dem Dashboard, ohne
das Login-Formular zu sehen.

## Technische Notizen

Umsetzung über Laravels Standard-`guest`-Middleware
(`Illuminate\Auth\Middleware\RedirectIfAuthenticated`). Ziel-Route ist die
benannte Route `dashboard` (Laravel-Default). Keine zusätzliche
Konfiguration in `bootstrap/app.php` nötig.