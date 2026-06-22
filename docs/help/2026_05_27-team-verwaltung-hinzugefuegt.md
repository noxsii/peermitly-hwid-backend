# Team-Verwaltung hinzugefügt

## Datum

2026-05-27

## Bereich

Team, Benutzermenü, Einstellungen

## Kurzbeschreibung

Administratoren können den Namen ihrer eigenen Teams jetzt direkt in der Anwendung ändern. Ein neuer Menüpunkt "Team" wurde im Benutzermenü oben rechts ergänzt.

## Was ist neu?

- Neuer Menüpunkt "Team" im Benutzermenü (oben rechts, zwischen "Settings" und "Log out").
- Neue Seite `/team`, auf der alle eigenen Teams als Karten angezeigt werden.
- Jede Karte enthält ein Eingabefeld für den Teamnamen und einen "Save"-Button.

## Warum wurde das geändert?

Bislang konnte der Teamname nur durch einen Super-Administrator über das Admin-Panel geändert werden. Mit der neuen Seite können Team-Eigentümer ihre Teams selbstständig umbenennen, ohne den Support zu kontaktieren.

## Wie funktioniert es?

1. Oben rechts auf das eigene Profilbild klicken, um das Benutzermenü zu öffnen.
2. Auf "Team" klicken (nur sichtbar für Administratoren und Super-Administratoren).
3. Auf der Team-Seite wird für jedes eigene Team eine Karte angezeigt.
4. Den gewünschten neuen Namen ins Eingabefeld eintragen.
5. Auf "Save" klicken. Der Name wird sofort gespeichert.

## Betroffene Bereiche

- Benutzermenü im Header (`UserMenu`).
- Neue Seite `/team`.
- Datenbanktabelle `teams` (Spalte `name`).

## Wichtige Hinweise

- Der Menüpunkt ist nur für Benutzer mit der Rolle "Admin" oder "Super-Admin" sichtbar. Reguläre Benutzer sehen weder den Menüpunkt noch können sie die Seite direkt aufrufen.
- Es können ausschließlich Teams umbenannt werden, deren Eigentümer der eingeloggte Benutzer ist. Fremde Teams werden nicht angezeigt und können nicht bearbeitet werden.
- Der Teamname darf maximal 255 Zeichen lang sein und darf nicht leer sein.
- Andere Team-Funktionen (Mitglieder verwalten, Besitzer wechseln, Team erstellen oder löschen) sind in dieser Änderung nicht enthalten.

## Beispiel

Ein Administrator besitzt zwei Teams: "Alpha" und "Beta". Nach dem Öffnen von `/team` sieht er beide als Karten. Er ändert "Alpha" zu "Acme GmbH", klickt auf "Save" und der neue Name wird gespeichert. Ab sofort wird "Acme GmbH" überall dort angezeigt, wo zuvor "Alpha" stand.

## Technische Notizen

- Routen: `GET /team` (`team.index`) und `PATCH /team/{team:uuid}` (`team.update`), definiert in `routes/team.php`.
- Geschützt per Middleware `role:admin,super_admin` und zusätzlich per FormRequest-Ownership-Prüfung gegen den Team-Owner.
- Persistenz erfolgt über `App\Actions\Team\UpdateTeamAction` mit DTO `App\Data\Team\UpdateTeamData`.
- Frontend: Seite `resources/js/pages/team/Index.vue` mit deferred Prop `teams`, Karte pro Team in `resources/js/components/team/TeamCard.vue`.
