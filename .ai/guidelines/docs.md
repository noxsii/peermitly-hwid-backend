# Docs für das Projekt

## Ziel

Nach jeder größeren Änderung, jedem neuen Feature, jeder relevanten Anpassung am Verhalten oder jeder Änderung an bestehenden Abläufen muss automatisch eine neue Dokumentationsdatei erstellt werden.

Diese Dokumentation dient später als Grundlage für die Hilfe-Seite, interne Nachvollziehbarkeit und Änderungsverfolgung.

---

## Pflicht: Dokumentation nach Änderungen

1. Nach jeder größeren Änderung oder jedem neuen Feature muss im Ordner `docs/help/` eine neue Markdown-Datei erstellt werden.
2. Die Datei muss alle wichtigen Informationen zu der Änderung enthalten.
3. Es darf nicht einfach bestehender Text überschrieben werden. Für jede größere Änderung ist eine eigene neue Datei anzulegen.
4. Kleine interne Refactorings ohne sichtbare Auswirkung müssen nicht dokumentiert werden, außer sie verändern Abläufe, Logik oder Bedienung.

---

## Dateiname

Der Dateiname muss immer mit dem aktuellen Datum beginnen.

**Format:**

`docs/help/2026_MM_DD-kurze-beschreibung.md`

**Beispiele:**

- `docs/help/2026_04_10-neue-kundenverwaltung.md`
- `docs/help/2026_04_10-rechnung-storno-hinzugefuegt.md`
- `docs/help/2026_04_11-tourenplanung-optimiert.md`

**Wichtig:**

- Immer `2026_MM_DD` verwenden
- Danach eine kurze, klare Beschreibung in Kleinbuchstaben
- Wörter mit Bindestrichen trennen
- Keine Umlaute im Dateinamen
- Keine Leerzeichen verwenden

---

## Wann eine Doku-Datei erstellt werden muss

Erstelle immer eine Datei, wenn mindestens einer dieser Punkte zutrifft:

- neues Feature
- sichtbare UI-Änderung
- neuer Ablauf
- bestehender Ablauf wurde verändert
- neue Einstellung oder Konfiguration
- neue Rolle, Berechtigung oder Einschränkung
- neue Schnittstelle oder Integration
- Validierung oder Verhalten wurde angepasst
- Datenstruktur wurde erweitert, wenn das für Benutzer oder Support relevant ist
- Fehler wurde behoben, der Auswirkungen auf die Nutzung hatte

---

## Inhalt der Datei

Jede Datei in `docs/help/` muss klar und verständlich aufgebaut sein und mindestens diese Bereiche enthalten:

# Titel der Änderung

## Datum
2026-04-10

## Bereich
Zum Beispiel: Kunden, Rechnungen, Tourenplanung, Benutzer, Einstellungen

## Kurzbeschreibung
Kurze Erklärung, was geändert wurde.

## Was ist neu?
Beschreibung der neuen Funktion oder Änderung.

## Warum wurde das geändert?
Kurze Begründung oder Problemstellung.

## Wie funktioniert es?
Schritt-für-Schritt-Erklärung der neuen oder geänderten Funktion.

## Betroffene Bereiche
Liste der betroffenen Seiten, Masken, Prozesse oder Module.

## Wichtige Hinweise
Besonderheiten, Einschränkungen oder Dinge, die beachtet werden müssen.

## Beispiel
Wenn sinnvoll, ein kurzes Praxisbeispiel.

## Technische Notizen
Optional: technische Details, nur wenn sie für spätere Pflege oder Support relevant sind.

---

## Schreibstil

Die Dokumentation muss:

- klar
- einfach verständlich
- konkret
- hilfreich für spätere Hilfe-Texte
- ohne unnötigen Entwicklerjargon

geschrieben sein.

Schreibe so, dass der Text später leicht für eine Hilfe-Seite, FAQ oder Benutzeranleitung wiederverwendet werden kann.

---

## Wichtige Regeln

- Keine Änderung ohne passende Datei in `docs/help/`
- Lieber eine kurze brauchbare Datei als gar keine
- Die Datei muss den tatsächlichen Stand der Änderung beschreiben
- Keine Platzhaltertexte wie `TODO`
- Keine rein technischen Commit-Beschreibungen
- Immer benutzerorientiert dokumentieren, nicht nur aus Entwicklersicht

---

## Verhalten für Claude

Wenn du eine größere Änderung oder ein neues Feature umsetzt, musst du am Ende zusätzlich eine neue Datei in `docs/help/` anlegen.

Du sollst dabei:

1. das aktuelle Datum verwenden
2. einen sinnvollen Dateinamen erzeugen
3. die Änderung sauber und verständlich dokumentieren
4. so schreiben, dass der Inhalt später für die Hilfe-Seite genutzt werden kann

Diese Dokumentation ist verpflichtender Teil jeder größeren Änderung.
