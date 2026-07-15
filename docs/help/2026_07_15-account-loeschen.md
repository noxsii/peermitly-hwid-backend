# Account löschen

## Datum

2026-07-15

## Bereich

Benutzer, Profil, Sicherheit

## Kurzbeschreibung

Benutzer können ihren eigenen Account jetzt selbst dauerhaft löschen. Dabei werden alle zum Account gehörenden Daten mit entfernt.

## Was ist neu?

Auf der Profil-Seite gibt es eine neue Box "Danger Zone" mit der Funktion "Delete account". Über diese Box kann der eingeloggte Benutzer sein eigenes Konto endgültig löschen.

## Warum wurde das geändert?

Bisher konnten Benutzer ihren Account nicht selbst entfernen. Für Datenschutz und Selbstverwaltung war eine Möglichkeit nötig, den eigenen Zugang samt aller Daten vollständig zu löschen.

## Wie funktioniert es?

1. Der Benutzer öffnet die Seite "Profile".
2. In der Box "Danger Zone" gibt er sein aktuelles Passwort ein.
3. Nach Klick auf "Delete account" wird der Account samt allen Daten gelöscht.
4. Der Benutzer wird sofort abgemeldet und auf die Login-Seite geleitet.

## Betroffene Bereiche

- Profil-Seite (neue Box "Danger Zone")
- Anmeldung/Abmeldung (automatischer Logout nach Löschung)

## Wichtige Hinweise

- Die Löschung ist endgültig und kann nicht rückgängig gemacht werden.
- Das aktuelle Passwort muss korrekt eingegeben werden, sonst wird die Löschung abgelehnt.
- Mit dem Account werden entfernt: Subscription, API-Keys (Tokens), Passkeys, Benachrichtigungen und das Aktivitätsprotokoll des Benutzers.

## Beispiel

Ein Benutzer möchte den Dienst nicht mehr nutzen. Er öffnet "Profile", gibt sein Passwort in der Box "Danger Zone" ein und klickt auf "Delete account". Sein Konto und alle zugehörigen Daten sind danach vollständig gelöscht.

## Technische Notizen

- Route: `DELETE /settings/account` (`settings.account.destroy`).
- Ablauf: `DeleteAccountRequest` (Regel `current_password`) → `AccountController@destroy` → `LogoutAction` → `DeleteAccountAction`.
- `DeleteAccountAction` löscht in einer DB-Transaktion: Notifications, Activity-Log (subject + causer), Passkeys, Sanctum-Tokens, Subscriptions und zuletzt den Benutzer.