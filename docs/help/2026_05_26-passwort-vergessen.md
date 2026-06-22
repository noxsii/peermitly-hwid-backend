# Passwort vergessen + Reset per Magic Link

## Datum

2026-05-26

## Bereich

Login, Sicherheit, E-Mail, Auth

## Kurzbeschreibung

Nutzer können auf der Login-Seite jetzt **Forgot password?** anklicken,
ihre Email eintragen und bekommen einen Magic Link per Mail zugeschickt.
Über diesen Link können sie ein neues Passwort vergeben.

## Was ist neu?

- Link **Forgot password?** auf der Login-Seite (`/login`) — rechts neben
  „Remember me".
- Neue Seite `/forgot-password` mit einem einzelnen Email-Eingabefeld.
- Reset-Mail mit Branded Layout (Peermitly-Optik, Outlook-tauglich).
- Neue Seite `/reset-password/{token}` mit neuem Passwort + Bestätigung.
- Magic Link ist **60 Minuten** gültig (Default aus
  `config/auth.php`).
- Nach erfolgreichem Reset Weiterleitung auf `/login` mit Status-Meldung.

## Warum wurde das geändert?

Bisher gab es keine Möglichkeit, ein verlorenes Passwort selbst
zurückzusetzen. Support musste das Passwort manuell ändern. Jetzt ist
der Standard-Self-Service-Flow möglich, ohne Personal zu binden.

## Wie funktioniert es?

1. Nutzer klickt auf der Login-Seite auf **Forgot password?**.
2. Auf `/forgot-password` Email-Adresse eintragen und absenden.
3. System schickt eine Mail mit einem Reset-Link (gequeued, läuft über
   Horizon-Worker).
4. Nutzer klickt den Link → landet auf `/reset-password/{token}`.
5. Neues Passwort + Bestätigung eintragen und speichern.
6. Weiterleitung auf `/login` mit Erfolgsmeldung — neues Passwort
   funktioniert sofort.

## Betroffene Bereiche

- Routen in `routes/auth.php` — vier neue Endpoints unter dem
  `guest`-Middleware-Block:
  - `GET /forgot-password` → `password.request`
  - `POST /forgot-password` → `password.email`
  - `GET /reset-password/{token}` → `password.reset`
  - `POST /reset-password` → `password.update`
- Neue Controller:
  - `App\Http\Controllers\Auth\ForgotPasswordController`
  - `App\Http\Controllers\Auth\ResetPasswordController`
- Neue Actions:
  - `App\Actions\Auth\SendPasswordResetLinkAction` — kapselt
    `Password::sendResetLink()`.
  - `App\Actions\Auth\ResetUserPasswordAction` — kapselt
    `Password::reset()` inkl. Hashing, Remember-Token, `PasswordReset` Event.
- Form Requests:
  - `App\Http\Requests\Auth\ForgotPasswordRequest`
  - `App\Http\Requests\Auth\ResetPasswordRequest` (nutzt
    `Password::defaults()` für Komplexitätsregel und `confirmed`).
- Neue Notification `App\Notifications\ResetPasswordNotification`
  (`ShouldQueue`) — überschreibt den Default-Notification von Laravel,
  damit die Branded `<x-emails.layout>`-Hülle verwendet wird.
- `User::sendPasswordResetNotification()` Override — leitet auf die neue
  Notification um.
- Neue Inertia-Seiten:
  - `resources/js/pages/auth/ForgotPassword.vue`
  - `resources/js/pages/auth/ResetPassword.vue`
- `resources/js/pages/auth/Login.vue` — neuer **Forgot password?** Link.
- Mail-Template `resources/views/emails/auth/reset-password.blade.php` —
  benutzt die wiederverwendbare `<x-emails.layout>`-Hülle, bulletproof
  Button (VML für Outlook), Klartext-URL als Fallback.
- Pest-Tests `tests/Feature/Auth/PasswordResetTest.php` — 8 Tests:
  Render, Notification dispatched, Unknown Email Fehler, Invalid Email
  Format, Reset-Seite Render mit Token, erfolgreicher Reset, ungültiges
  Token, Passwort-Bestätigung muss matchen.

## Wichtige Hinweise

- **Queue**: Die Reset-Notification ist `ShouldQueue`. Damit die Mail
  rausgeht, muss Horizon laufen (`php artisan horizon` oder
  `composer dev`).
- **Token-Tabelle**: `password_reset_tokens` (existiert bereits durch
  das initiale `create_users_table`-Migration).
- **Sicherheit**: Bei unbekannter Email-Adresse zeigt Laravels Password
  Broker einen Validation-Fehler. Wer das nicht möchte, kann den Flow
  in der Action umstellen, sodass immer eine Erfolgsmeldung zurückkommt
  (User-Enumeration vermeiden). Aktuell ist Default-Verhalten aktiv.
- **Gültigkeitsdauer**: 60 Minuten, konfigurierbar in
  `config/auth.php` unter `passwords.users.expire`.
- **Throttling**: Default 60 Sekunden zwischen zwei Reset-Anfragen pro
  Email — verhindert Spamming.

## Beispiel

> Eine Nutzerin hat ihr Passwort vergessen. Sie klickt auf `/login`
> auf **Forgot password?**, gibt `ada@example.com` ein und sieht eine
> grüne Erfolgsmeldung. Kurz darauf liegt im Postfach eine Mail mit
> dem Betreff „Reset your Peermitly password" und einem großen
> **Reset password** Button. Sie klickt drauf, landet auf
> `/reset-password/<token>`, gibt zweimal das neue Passwort ein, klickt
> **Reset password** und wird auf `/login` zurückgeleitet —
> „Your password has been reset. You can sign in with the new password."

## Technische Notizen

- `Password::sendResetLink` triggert
  `Notifiable::sendPasswordResetNotification`. Wir überschreiben das
  im `User`-Model, um unsere eigene Mail-View zu nutzen statt der
  Laravel-Default-Markdown-Variante.
- Die `ResetUserPasswordAction` ruft `Password::reset()` mit einer
  Callback, die `password` per `Hash::make()` setzt und einen neuen
  `remember_token` generiert. Bei Erfolg wird `PasswordReset` Event
  dispatched (Laravel-Standard).
- Form-Requests werfen `ValidationException` über die Action-Aufrufe,
  die landen im üblichen Inertia-Fehler-Flow (`errors.email` etc.).
- Tests faken `Notification::fake()` und prüfen, dass die
  `ResetPasswordNotification` an den passenden User geht.
