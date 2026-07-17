# Blinkende Header auf Landingpage und Dokumentation behoben

## Datum

2026-07-17

## Bereich

Landingpage und Dokumentation

## Kurzbeschreibung

Die Kopfzeilen der Landingpage und Dokumentation bleiben beim Laden und Scrollen jetzt stabil und blinken oder springen nicht mehr.

## Was ist neu?

Beide Header verwenden eine konstante Höhe und eine deckende Hintergrundfläche. Der scrollabhängige Wechsel der Landingpage-Navigation und der aufwendige Hintergrund-Weichzeichner wurden entfernt.

## Warum wurde das geändert?

Halbtransparente, weichgezeichnete und fixierte Flächen müssen vom Browser beim Scrollen wiederholt neu zusammengesetzt werden. Zusammen mit dem nachträglich ermittelten Scrollzustand konnte das insbesondere auf mobilen Safari-Browsern als kurzes Blinken oder Springen sichtbar werden.

## Wie funktioniert es?

1. Die Landingpage zeigt den Header unabhängig von der Scrollposition immer im gleichen Zustand.
2. Die Dokumentation behält weiterhin ihren angehefteten Header.
3. Hintergrund, Rahmen und Höhe ändern sich beim Scrollen nicht mehr.
4. Navigation, mobile Menüs und Theme-Schalter funktionieren unverändert.

## Betroffene Bereiche

- Landingpage-Navigation auf Desktop und Mobilgeräten
- Dokumentations-Header auf Desktop und Mobilgeräten
- Scrollverhalten und Seitenaufruf

## Wichtige Hinweise

Die Änderung entfernt ausschließlich den visuellen Blur- und Scrollzustandswechsel. Inhalte und Navigationsziele wurden nicht verändert.

## Beispiel

Beim langsamen Scrollen über den oberen Seitenbereich wechselt der Landingpage-Header nicht mehr zwischen zwei Hintergrundzuständen und bleibt dadurch ruhig stehen.

## Technische Notizen

Die Header nutzen nun deckende Hintergrundfarben und feste Höhen. Dadurch entfällt eine instabile, scrollabhängige Compositing-Ebene.
