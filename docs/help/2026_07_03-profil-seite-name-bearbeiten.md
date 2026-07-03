# Profil-Seite zum Bearbeiten des Namens

## Datum

2026-07-03

## Bereich

Dashboard, Profil, Konto

## Kurzbeschreibung

Im Dashboard gibt es links einen neuen Punkt **Profil**. Dort kann der Nutzer in einer Box seinen Namen bearbeiten.

## Was ist neu?

- Neuer Sidebar-Punkt **Profil** (`/profile`).
- Box „Profile" mit Eingabefeld für den **Namen** und Speichern-Button.
- Die E-Mail wird angezeigt, ist hier aber nicht änderbar.
- Nach dem Speichern erscheint kurz eine Bestätigung („Saved").

## Warum wurde das geändert?

Nutzer sollen ihren angezeigten Namen selbst anpassen können.

## Wie funktioniert es?

1. Links im Dashboard auf **Profil** klicken.
2. Im Feld **Name** den neuen Namen eintragen.
3. Auf **Save** klicken — der Name wird gespeichert.

## Betroffene Bereiche

- Dashboard-Sidebar (neuer Punkt)
- Neue Seite `/profile`

## Wichtige Hinweise

- Der Name ist ein Pflichtfeld.
- Die E-Mail-Adresse kann auf dieser Seite nicht geändert werden.

## Technische Notizen

- Eigene Route-Datei `routes/profile.php` (Prefix `profile`, Namen `profile.edit` / `profile.update`), registriert im `RouteServiceProvider`.
- `ProfileController`, `UpdateProfileAction`, `UpdateProfileRequest`.
- Frontend: `pages/Profile.vue` mit `components/settings/ProfileCard.vue`.
- Test: `tests/Feature/Settings/ProfileTest.php`.
