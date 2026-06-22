# Benachrichtigung per Mail bei jedem Login

## Datum

2026-05-21

## Bereich

Login, Sicherheit, E-Mail, Templates

## Kurzbeschreibung

Nach jeder erfolgreichen Anmeldung über `/login` schickt Peermitly automatisch
eine E-Mail an die Adresse des Benutzers. Die Mail informiert über die
Anmeldung und listet Zeit, IP-Adresse und Gerät auf, sodass auffällige
Logins sofort erkennbar sind.

## Was ist neu?

- Neue E-Mail-Vorlage **New sign-in to your Peermitly account**.
- Wird direkt im `LoginController::store` ausgelöst, sobald die Anmeldung
  erfolgreich war.
- Outlook- und Mobile-tauglich: tabellenbasiertes Layout, Inline-Styles,
  bulletproof Button via VML.
- Wiederverwendbare Layout-Komponente unter
  `resources/views/components/emails/layout.blade.php`. Künftige
  System-Mails können dieselbe Hülle nutzen (`<x-emails.layout>`).

## Warum wurde das geändert?

Ohne diese Benachrichtigung kann ein Nutzer einen unautorisierten Login nicht
selbst erkennen. Die Mail dient als einfache Security-Maßnahme und ist
unabhängig vom Login-Quellsystem hilfreich (z.B. Audit-Trail im Postfach).

## Wie funktioniert es?

1. Nutzer trägt Email + Passwort auf `/login` ein und schickt das Formular ab.
2. `AttemptLoginAction` prüft die Credentials und regeneriert die Session.
3. War der Login erfolgreich, hängt `LoginController::store` direkt eine
   `NewLoginNotification` an die Mail-Queue (`ShouldQueue`).
4. Der Worker stellt die Mail zu (HTML, kein Plain-Text-Fallback).
5. Bei fehlgeschlagenem Login (falsches Passwort, unbekannte Adresse, …)
   wird keine Mail verschickt.

## Betroffene Bereiche

- `app/Http/Controllers/LoginController.php` – verschickt die Mail nach
  erfolgreichem `AttemptLoginAction`.
- `app/Mail/NewLoginNotification.php` – neues Mailable, `ShouldQueue`.
- `resources/views/emails/auth/new-login.blade.php` – Mail-Inhalt.
- `resources/views/components/emails/layout.blade.php` – wiederverwendbare
  Layout-Hülle für alle künftigen System-Mails.
- `tests/Feature/Auth/LoginNotificationTest.php` – Tests.

## Wichtige Hinweise

- **Queue erforderlich**: Das Mailable implementiert `ShouldQueue`. Damit die
  Mail tatsächlich rausgeht, muss ein Queue-Worker laufen (`php artisan queue:work`)
  bzw. der konfigurierte Queue-Driver muss synchron arbeiten (`sync`).
- **MAIL_FROM_ADDRESS / MAIL_FROM_NAME** in der `.env` bestimmen Absender.
  Falls dort nichts gesetzt ist, greift der Default aus `config/mail.php`
  (`hello@example.com` / `Example`) — vor dem Produktiv-Einsatz unbedingt setzen.
- **Outlook-Kompatibilität**: Layout ist mit `<table>`-Konstrukten und
  Inline-Styles aufgebaut. Der CTA-Button enthält VML für ältere Outlook-
  Versionen. Es werden keine Webfonts geladen — Fallback auf Arial/Helvetica.
- **Filament-Login**: Erzeugt keine Mail. Diese Implementierung dispatcht
  ausschließlich aus `LoginController::store`, also nur beim regulären
  Frontend-Login.

## Beispiel

> Eine Nutzerin meldet sich um 09:42 Uhr von Berlin (IP 203.0.113.42) am
> Peermitly-Frontend an. Sekunden später liegt in ihrem Postfach eine Mail
> mit dem Betreff „New sign-in to your Peermitly account". Darin: ihre
> Account-Adresse, die genaue Zeit, die IP und der User-Agent. Sie erkennt
> ihren eigenen Login und ignoriert die Mail. Tauchte stattdessen eine
> unbekannte IP auf, könnte sie sofort das Passwort ändern.

## Technische Notizen

- Daten, die ans Template übergeben werden: `userName`, `userEmail`,
  `ipAddress`, `userAgent`, `loggedInAt` (Carbon, wird im Template auf die
  App-Timezone umgestellt).
- IP via `Request::ip()`, User-Agent via `Request::userAgent()`. Sind diese
  Werte leer (z.B. CLI-Aufrufe), wird `'unknown'` eingetragen.
- Tests fakten den Mailer per `Mail::fake()` und prüfen `assertQueued`
  (wegen `ShouldQueue`). Das gerenderte HTML wird auf die korrekten
  Platzhalter geprüft (Email, IP, User-Agent, Überschrift).
- Beim Hinzufügen weiterer System-Mails: Layout-Komponente
  `<x-emails.layout :title="..." :preheader="...">{{ $slot }}</x-emails.layout>`
  benutzen, damit Branding und Outlook-Kompatibilität konsistent bleiben.
