# News-Bereich

## Datum

2026-07-16

## Bereich

News, Startseite, Admin

## Kurzbeschreibung

Es gibt jetzt einen News-Bereich. Im Admin können Beiträge erstellt werden, im
Frontend erscheinen sie als Übersicht und als einzelne Detailseite.

## Was ist neu?

- Neue Seite „News" unter `/news` mit einer Karten-Übersicht (Bild, Titel,
  Kurzbeschreibung, Datum).
- Detailseite pro Beitrag unter `/news/<slug>` mit großem Bild und vollem Text.
- Neuer Admin-Bereich „News" (Gruppe „Content") zum Anlegen und Bearbeiten.
- Link „News" im Footer.

## Warum wurde das geändert?

Es fehlte eine Möglichkeit, Ankündigungen und Neuigkeiten ansprechend zu
veröffentlichen.

## Wie funktioniert es?

1. Im Admin „News" öffnen und „Create" klicken.
2. Titel, Kurzbeschreibung, optional ein Bild und den Textinhalt eingeben.
3. „Published at" setzen, damit der Beitrag öffentlich sichtbar wird. Ohne
   Datum bleibt der Beitrag ein Entwurf.
4. Der Link (Slug) wird automatisch aus dem Titel erzeugt, kann aber überschrieben
   werden.

## Betroffene Bereiche

- Frontend: `/news`, `/news/<slug>`, Footer
- Admin: News-Verwaltung

## Wichtige Hinweise

- Nur Beiträge mit gesetztem „Published at" erscheinen im Frontend.
- Das Bild ist optional; ohne Bild wird ein Farbverlauf angezeigt.
- Detailseiten enthalten SEO-Meta-Tags (Titel, Beschreibung, Open Graph, Twitter).