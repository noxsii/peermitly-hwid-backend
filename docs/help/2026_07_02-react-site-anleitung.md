# React-Anleitung hinzugefügt

## Datum

2026-07-02

## Bereich

Dokumentation, Sites

## Kurzbeschreibung

Es gibt jetzt eine eigene Hilfe-Seite für das Erstellen von **React**-Sites, passend zur bestehenden Vue-Anleitung.

## Was ist neu?

- Neue Doku-Seite **React** unter `/guide/react`.
- Sie erscheint im Menü unter **Sites**, direkt nach **Vue**.
- Die Anleitung erklärt, wie eine React-App über HTTPS auf einer `.peer`-Domain läuft, welche Optionen es beim Erstellen gibt und wie der Dev-Server gestartet wird.

## Warum wurde das geändert?

Für Vue gab es bereits eine Anleitung. React fehlte, obwohl der gleiche Ablauf gilt. Damit ist die Doku für beide Frameworks vollständig.

## Wie funktioniert es?

1. In den Docs im Bereich **Sites** den Punkt **React** öffnen.
2. Die Seite beschreibt Schritt für Schritt: Voraussetzungen (Node, nginx, DNS), Optionen (TypeScript, Router, State, …), Port, HTTPS und das Starten des Dev-Servers.

## Betroffene Bereiche

- Docs-Menü (Sidebar)
- Neue Seite `/guide/react`

## Wichtige Hinweise

- Der Dev-Server muss laufen (`npm run dev`), damit `https://your-name.peer` die App anzeigt.
- Für React-Sites wird Node benötigt.

## Technische Notizen

- Inhalt: `resources/js/docs/react.md`
- Navigation/gültiger Slug: `config/docs.php` (Abschnitt **Sites**)
- Test: `tests/Feature/DocsTest.php` prüft, dass `/guide/react` rendert.