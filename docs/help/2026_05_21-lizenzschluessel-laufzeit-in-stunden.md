# Lizenzschlüssel mit Laufzeit in Stunden

## Datum

2026-05-21

## Bereich

Lizenzschlüssel

## Kurzbeschreibung

Lizenzschlüssel können jetzt zusätzlich zu Tagen, Wochen, Monaten, Jahren und Lifetime auch mit einer Laufzeit in **Stunden** angelegt und verlängert werden.

## Was ist neu?

- Neue Einheit **Stunden** in allen Auswahlfeldern zur Laufzeit eines Lizenzschlüssels.
- Die Stunden-Einheit ist verfügbar bei:
    - Erstellen eines einzelnen Lizenzschlüssels
    - Bulk-Erstellung mehrerer Lizenzschlüssel
    - Verlängern (Extend) eines bestehenden Schlüssels
- Auf der Detailseite eines Lizenzschlüssels wird die Restlaufzeit bei stundenbasierten Schlüsseln in Stunden statt in Tagen angezeigt.

## Warum wurde das geändert?

Für kurzlebige Anwendungsfälle (z. B. Testlizenzen, Demos, zeitlich stark begrenzte Zugriffe) ist eine Auflösung in Tagen zu grob. Mit Stunden lassen sich solche Szenarien sauber abbilden.

## Wie funktioniert es?

1. Beim Erstellen oder Verlängern eines Lizenzschlüssels im Feld **Einheit** die Option **Hours** wählen.
2. Im Feld **Anzahl** die gewünschte Stundenzahl eingeben (z. B. `24` für 24 Stunden).
3. Speichern bzw. verlängern.
4. Der Schlüssel läuft ab dem Aktivierungszeitpunkt nach der eingestellten Stundenzahl ab.

## Betroffene Bereiche

- Lizenzschlüssel anlegen (Dialog)
- Lizenzschlüssel-Bulk-Erstellung (Dialog)
- Lizenzschlüssel-Detailseite – Karte „Extend"
- Anzeige der Restlaufzeit auf der Detailseite

## Wichtige Hinweise

- Bestehende Lizenzschlüssel sind nicht betroffen, ihre Einheit bleibt unverändert.
- Die Wahl der Einheit **Lifetime** ignoriert die Stundenzahl wie bisher.

## Beispiel

Eine 48-Stunden-Demolizenz:

- Einheit: **Hours**
- Anzahl: **48**

Nach Aktivierung läuft der Schlüssel exakt 48 Stunden nach Aktivierungszeitpunkt ab.

## Technische Notizen

- Enum `App\Enums\LicenseValidityUnit` um Case `HOURS` erweitert.
- `applyTo()` nutzt `Carbon::addHours()` für die neue Einheit.
- TypeScript-Union `LicenseValidityUnit` um `"hours"` erweitert.
- Restlaufzeitanzeige in `pages/license-keys/Show.vue` rechnet bei `validity_unit === "hours"` in Stunden.