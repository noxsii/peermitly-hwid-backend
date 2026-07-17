# Admin-Center im neuen Peermitly-Layout

## Datum

2026-07-17

## Bereich

Admin-Center

## Kurzbeschreibung

Das Filament-Admin-Center verwendet jetzt dieselbe klare Gestaltung und grüne Akzentfarbe wie Landingpage, Dokumentation und Dashboard.

## Was ist neu?

Navigation, Kopfzeile, Karten, Tabellen, Formulare und Dialoge wurden optisch vereinheitlicht. Die Seitenleiste ist kompakter und kann auf dem Desktop eingeklappt werden. Heller und dunkler Modus nutzen ruhige, neutrale Flächen mit Grün als gezielter Aktionsfarbe.

## Warum wurde das geändert?

Das Admin-Center wirkte bisher wie ein getrenntes Standard-Backend und nutzte noch Orange als Primärfarbe. Die neue Gestaltung schafft einen durchgängigen Markenauftritt und verbessert die visuelle Orientierung.

## Wie funktioniert es?

1. Das Admin-Center wie gewohnt unter `/admin` öffnen.
2. Die Navigation links für den Wechsel zwischen den Verwaltungsbereichen verwenden.
3. Bei Bedarf die Seitenleiste über den Schalter in der Kopfzeile einklappen.
4. Der gewählte helle oder dunkle Modus wird weiterhin von Filament verwaltet.

## Betroffene Bereiche

- Admin-Navigation und Kopfzeile
- Dashboard-Kennzahlen und Widgets
- Ressourcen-Tabellen
- Formulare, Dialoge und Dropdown-Menüs
- Heller und dunkler Darstellungsmodus
- Mobile Admin-Ansicht

## Wichtige Hinweise

Funktionen, Berechtigungen und Verwaltungsabläufe wurden nicht verändert. Die Anpassung betrifft ausschließlich Darstellung und Bedienkomfort.

## Beispiel

Der aktive Navigationspunkt wird nun dezent grün hervorgehoben, während alle übrigen Flächen neutral bleiben. Dadurch ist der aktuelle Bereich sofort sichtbar, ohne dass die Oberfläche unruhig wirkt.

## Technische Notizen

Das Admin-Center verwendet ein eigenes, über Vite kompiliertes Filament-Theme. Die Primärfarbe ist zentral in der Panel-Konfiguration hinterlegt.
