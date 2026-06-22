# Teamverwaltung im Filament Admin Panel

## Datum

2026-05-21

## Bereich

Verwaltung, Teams, Benutzer

## Kurzbeschreibung

Im Filament Admin Panel steht jetzt eine Ressource zur Verwaltung der Teams zur Verfügung. Super-Admins können Teams auflisten, anlegen, bearbeiten und löschen sowie Benutzer als Mitglieder hinzufügen oder entfernen.

## Was ist neu?

- Neuer Menüpunkt **Teams** im Filament Admin Panel.
- Übersichts-Tabelle mit Name, Owner, Mitgliederzahl und Suche/Sortierung.
- Formular zum Anlegen und Bearbeiten von Teams (Name + Owner-Auswahl).
- Auf der Bearbeiten-Seite eines Teams: Sektion **Members** mit Liste der zugeordneten Benutzer.
- **Attach**-Aktion: bestehende Benutzer dem Team als Mitglied hinzufügen (mehrere gleichzeitig möglich).
- **Detach**-Aktion: einzelne Benutzer oder per Mehrfachauswahl wieder entfernen.

## Warum wurde das geändert?

Teams sind der zentrale Mandant in der Anwendung. Super-Admins müssen Teams pflegen und die Zuordnung von Benutzern zu Teams direkt im Admin-Backend steuern können.

## Wie funktioniert es?

1. Als Super-Admin im Panel unter `/admin` einloggen.
2. In der linken Navigation auf **Teams** klicken.
3. Neues Team anlegen über **New team** (Name + Owner wählen).
4. Bestehendes Team per Stift-Icon bearbeiten.
5. Auf der Bearbeiten-Seite unter **Members**:
    - **Attach**: bestehende Benutzer auswählen und hinzufügen.
    - Stift-/Detach-Icon pro Zeile: einzelnen Benutzer entfernen.
    - Mehrfachauswahl: mehrere Mitglieder gleichzeitig entfernen.

## Betroffene Bereiche

- Admin Panel: `/admin/teams`
- Team-Anlegen-Formular (`/admin/teams/create`)
- Team-Bearbeiten-Formular (`/admin/teams/{uuid}/edit`)
- Mitgliederverwaltung als RelationManager auf der Bearbeiten-Seite

## Wichtige Hinweise

- Nur Benutzer mit der Rolle **Super-Admin** sehen und nutzen die Ressource.
- Die Mitgliederliste basiert auf der `team_user`-Pivot-Tabelle (Many-to-Many).
- Beim **Detach** wird nur die Pivot-Zeile entfernt, der Benutzer selbst bleibt bestehen.
- Der Owner wird über `owner_id` als separates Feld am Team gespeichert — Owner ist nicht automatisch Mitglied. Wenn der Owner auch in der Mitgliederliste auftauchen soll, muss er zusätzlich attached werden.

## Beispiel

Ein Super-Admin will einem neuen Team zwei Benutzer als Mitglieder zuordnen:

1. `/admin/teams` → **New team** → Name `Acme Inc.`, Owner Alice → speichern.
2. Bearbeiten öffnen → **Members** → **Attach** → Bob und Carol auswählen → speichern.
3. Die Mitglieder-Tabelle zeigt Bob und Carol mit Beitritts-Zeitstempel an.

## Technische Notizen

- Ressourcen-Klasse: `App\Filament\Resources\Teams\TeamResource`
- Formular: `App\Filament\Resources\Teams\Schemas\TeamForm`
- Tabelle: `App\Filament\Resources\Teams\Tables\TeamsTable`
- RelationManager: `App\Filament\Resources\Teams\RelationManagers\UsersRelationManager` (BelongsToMany via `team_user`)
- Mitgliederzahl in der Übersicht via `TextColumn::counts('users')`.
- Generiert via `php artisan make:filament-resource Team --generate` + `php artisan make:filament-relation-manager TeamResource users name`.
- Tests: 2 zusätzliche Cases in `tests/Feature/FilamentAccessTest.php` (Super-Admin darf Teams-Liste sehen, regulärer User bekommt 403).