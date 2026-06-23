# Sicherheitscode pro Benutzer

## Datum

2026-06-23

## Bereich

Benutzer, Sicherheit, Support, Dashboard, Filament

## Kurzbeschreibung

Jeder Benutzer erhält automatisch einen persönlichen 4-stelligen Sicherheitscode. Der Code dient dazu, die Identität des Benutzers zu prüfen, wenn er ein Support-Ticket erstellt.

## Was ist neu?

- Jeder neue Benutzer bekommt bei der Erstellung automatisch einen Sicherheitscode.
- Der Code besteht aus 4 gut lesbaren Zeichen (Buchstaben und Ziffern ohne Verwechslungsgefahr, z. B. ohne 0/O oder 1/I/L).
- Der Code wird verschlüsselt in der Datenbank gespeichert.
- Der Benutzer sieht seinen Code auf dem Dashboard.
- Im Admin-Bereich (Filament) ist der Code in der Benutzerliste sichtbar und im Bearbeiten-Formular änderbar.

## Warum wurde das geändert?

Beim Support muss sichergestellt werden, dass eine Anfrage wirklich vom richtigen Benutzer stammt. Über den persönlichen Code lässt sich die Identität schnell und einfach bestätigen.

## Wie funktioniert es?

1. Wird ein Benutzer angelegt, erzeugt das System automatisch einen 4-stelligen Code.
2. Der Code wird verschlüsselt gespeichert und beim Anzeigen wieder entschlüsselt.
3. Der Benutzer findet seinen Code auf dem Dashboard.
4. Erstellt ein Benutzer ein Ticket, sucht der Support im Admin-Bereich anhand der E-Mail-Adresse und gleicht den genannten Code mit dem hinterlegten Code ab.
5. Bei Bedarf kann ein Administrator den Code im Bearbeiten-Formular ändern.

## Betroffene Bereiche

- Benutzer-Dashboard (neue Karte „Your security code")
- Filament-Benutzerverwaltung (Spalte in der Liste, Feld im Formular)
- Datenbanktabelle `users` (neue Spalte `security_code`)

## Wichtige Hinweise

- Der Code wird verschlüsselt gespeichert, ist aber für Anzeige und Bearbeitung lesbar (kein Passwort-Hash).
- Wird beim Anlegen kein Code mitgegeben, erzeugt das System automatisch einen.
- Lässt ein Administrator das Feld beim Bearbeiten leer, bleibt der bisherige Code erhalten.
- Der Code wird nicht auf Eindeutigkeit über alle Benutzer geprüft, da er immer gemeinsam mit der E-Mail-Adresse geprüft wird.

## Beispiel

Ein Kunde schreibt ein Ticket und nennt seinen Code „K7QF". Der Support sucht den Kunden über seine E-Mail-Adresse, sieht den hinterlegten Code „K7QF" und bestätigt damit die Identität.

## Technische Notizen

- Spalte: `users.security_code` (`text`, nullable, Cast `encrypted`)
- Generierung: `App\Actions\Users\GenerateSecurityCodeAction` (Zeichensatz `23456789ABCDEFGHJKMNPQRSTUVWXYZ`)
- Automatik: `App\Observers\UserObserver::creating()`, registriert via `#[ObservedBy]` am `User`-Model
- Dashboard: `DashboardController` übergibt `securityCode` an `resources/js/pages/Dashboard.vue`
- Filament: Spalte in `UsersTable`, Feld in `UserForm`
- Tests: `tests/Unit/Actions/Users/GenerateSecurityCodeActionTest.php`, `tests/Feature/Users/SecurityCodeTest.php`, `tests/Feature/Filament/UserResourceTest.php`, `tests/Feature/Dashboard/DashboardPageTest.php`