# Benutzerverwaltung im Filament Admin Panel

## Datum

2026-05-21

## Bereich

Verwaltung, Benutzer

## Kurzbeschreibung

Im Filament Admin Panel steht jetzt eine Ressource zur Verwaltung der Benutzer zur Verfügung. Super-Admins können Benutzer auflisten, anlegen, bearbeiten und löschen.

## Was ist neu?

- Neuer Menüpunkt **Benutzer** im Filament Admin Panel.
- Übersichts-Tabelle mit Suche, Sortierung und Filtern (Rolle, E-Mail-Bestätigt-Status).
- Formular zum Anlegen neuer Benutzer.
- Formular zum Bearbeiten bestehender Benutzer (Passwort optional).
- Lösch-Aktion einzeln und in der Mehrfachauswahl.

## Warum wurde das geändert?

Damit Super-Admins Benutzeraccounts direkt im Admin-Backend pflegen können, ohne SQL oder Tinker zu benötigen.

## Wie funktioniert es?

1. Als Super-Admin im Filament Panel unter `/admin` einloggen.
2. In der linken Navigation auf **Benutzer** klicken.
3. **Neuer Benutzer**-Button: Formular ausfüllen (Name, E-Mail, Rolle, Passwort) und speichern.
4. Tabellenzeile per Stift-Symbol bearbeiten. Beim Bearbeiten darf das Passwortfeld leer bleiben, dann bleibt das alte Passwort erhalten.
5. Löschen über die Bearbeiten-Seite (Header-Aktion) oder per Mehrfachauswahl in der Tabelle.

## Betroffene Bereiche

- Admin Panel: `/admin/users`
- Benutzer-Tabelle (Liste)
- Benutzer-Anlegen-Formular (`/admin/users/create`)
- Benutzer-Bearbeiten-Formular (`/admin/users/{id}/edit`)

## Wichtige Hinweise

- Nur Benutzer mit der Rolle **Super-Admin** sehen und nutzen die Ressource (gleiche Zugriffsregel wie für das Panel selbst).
- Passwort wird beim Speichern automatisch mit Bcrypt gehasht.
- Beim Bearbeiten wird das Passwort nur dann überschrieben, wenn ein neuer Wert eingegeben wurde.
- E-Mail-Adresse ist eindeutig — doppelte Eingabe wird beim Speichern abgelehnt.
- Beim Anlegen lässt sich die Rolle frei wählen (`user`, `admin`, `super_admin`). Achtung: Ein neu erstellter Super-Admin hat sofort Zugriff auf das gesamte Panel.

## Beispiel

Ein bestehender Super-Admin will einem Kollegen Zugriff aufs Admin-Panel geben:

1. `/admin/users` öffnen.
2. **Neuer Benutzer** klicken.
3. Name, E-Mail, Passwort und Rolle **Super-Admin** wählen.
4. Speichern → der Kollege kann sich sofort unter `/admin/login` anmelden.

## Technische Notizen

- Ressourcen-Klasse: `App\Filament\Resources\Users\UserResource`
- Formular: `App\Filament\Resources\Users\Schemas\UserForm`
- Tabelle: `App\Filament\Resources\Users\Tables\UsersTable`
- Pages: `ListUsers`, `CreateUser`, `EditUser`
- Passwort-Hashing: `Hash::make()` in `dehydrateStateUsing`, kombiniert mit `dehydrated(filled($state))` für Edit-Optionalität.
- Generiert via `php artisan make:filament-resource User --generate`, danach Felder ins Deutsche übersetzt und Passwort-Handling, Unique-Validierung sowie Filter ergänzt.
- Tests: 2 zusätzliche Cases in `tests/Feature/FilamentAccessTest.php` (Super-Admin darf Liste sehen, regulärer User bekommt 403).