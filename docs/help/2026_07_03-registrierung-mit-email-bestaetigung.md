# Registrierung mit E-Mail-Bestätigung

## Datum

2026-07-03

## Bereich

Registrierung, Konto, E-Mail

## Kurzbeschreibung

Nutzer können sich jetzt selbst registrieren. Nach der Anmeldung muss die E-Mail über einen Link bestätigt werden, der 3 Stunden gültig ist.

## Was ist neu?

- Neue Seite **/register**: Name, E-Mail und Passwort (2×) auf einer Seite.
- Nach dem Absenden wird das Konto angelegt und erhält automatisch eine **Free**-Subscription.
- Der Nutzer bekommt eine **Bestätigungs-E-Mail** mit einem Link, der nach **3 Stunden** abläuft.
- Nach dem Klick wird das Konto **aktiv** geschaltet und die E-Mail als **bestätigt** markiert.
- Eigene, sauber gestaltete Bestätigungsseite (Erfolg / bereits bestätigt / Link ungültig).

## Warum wurde das geändert?

Bisher konnten sich Nutzer nicht selbst anmelden. Die Bestätigung stellt sicher, dass die E-Mail-Adresse echt ist, bevor das Konto aktiv wird.

## Wie funktioniert es?

1. Auf **/register** Name, E-Mail und Passwort eingeben.
2. Konto wird angelegt (zunächst inaktiv, unbestätigt) mit Free-Plan.
3. E-Mail mit Bestätigungslink kommt an (3 Stunden gültig).
4. Link anklicken → Bestätigungsseite bestätigt die E-Mail und aktiviert das Konto.
5. Danach normal einloggen.

## Betroffene Bereiche

- Neue Seiten: `/register`, Bestätigungsseite, „E-Mail bestätigen"-Hinweis
- Login-Seite: Link „Sign up" und Statushinweis nach Registrierung

## Wichtige Hinweise

- Der Bestätigungslink ist **3 Stunden** gültig. Danach über die Hinweisseite einen neuen anfordern.
- Vor der Bestätigung ist das Konto **nicht aktiv**.
- Jeder neue Nutzer startet mit einer **Free**-Subscription (kein Pro).

## Technische Notizen

- Routes in `routes/auth.php`: `register`, `register.store`, `verification.verify`, `verification.notice`, `verification.send`.
- Ablauf über signierten Link (`URL::temporarySignedRoute`, 3h) — Signatur/Ablauf werden im Controller geprüft.
- Actions: `RegisterUserAction`, `VerifyEmailAction`. Notification: `VerifyEmailNotification` (Mail-Queue).
