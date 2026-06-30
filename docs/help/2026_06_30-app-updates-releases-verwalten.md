# App-Updates über Releases verwalten

## Datum

2026-06-30

## Bereich

App-Updates, Releases, Filament, API

## Kurzbeschreibung

Es gibt jetzt einen neuen Bereich "App-Versionen" im Adminbereich. Dort werden die Versionen der Desktop-App (Tauri) gepflegt. Die App prüft beim Start automatisch, ob eine neuere Version vorhanden ist, und aktualisiert sich selbst. Die zugehörigen Dateien (Installer/Update-Pakete) werden direkt im Adminbereich hochgeladen – ein Zugriff auf den Server ist nicht mehr nötig.

## Was ist neu?

- Neuer Menüpunkt "App-Versionen" im Adminbereich (Gruppe "App").
- Pro Version können Release Notes, ein Veröffentlichungsdatum und mehrere Plattform-Einträge gepflegt werden.
- Jeder Plattform-Eintrag besteht aus: Plattform, hochgeladener Datei und Signatur.
- Die Update-Datei wird direkt im Formular hochgeladen (kein Server-Zugriff nötig).
- Nur als "Veröffentlicht" markierte Versionen werden an die App ausgeliefert.
- Neuer öffentlicher Endpunkt `GET /api/update/latest`, den die App automatisch abfragt.

## Warum wurde das geändert?

Bisher hätte jede neue App-Version manuell auf dem Server hinterlegt werden müssen. Jetzt lässt sich der komplette Update-Vorgang bequem über den Adminbereich steuern, inklusive Datei-Upload.

## Wie funktioniert es?

1. Neue Version der Desktop-App bauen. Der Build erzeugt pro Plattform eine Update-Datei und eine `.sig`-Signaturdatei.
2. Im Adminbereich unter "App-Versionen" auf "Neu" klicken.
3. Versionsnummer eintragen (exakt wie in der App, z. B. `0.2.0`).
4. Optional Release Notes eingeben (werden im Update-Dialog der App angezeigt).
5. Für jede Plattform einen Eintrag anlegen:
   - Plattform auswählen (z. B. macOS Apple Silicon, macOS Intel, Windows x64).
   - Update-Datei hochladen.
   - Inhalt der `.sig`-Datei in das Signatur-Feld einfügen.
6. "Veröffentlicht" aktivieren und speichern.
7. Die App prüft beim nächsten Start den Endpunkt, vergleicht die Version und aktualisiert sich bei Bedarf automatisch.

## Betroffene Bereiche

- Adminbereich: neuer Bereich "App-Versionen"
- Öffentlicher API-Endpunkt `GET /api/update/latest`
- Datei-Speicher (Festplatte des Servers, öffentlich erreichbar unter `/storage/releases/...`)

## Wichtige Hinweise

- **Erste Installation:** Die allererste Version müssen Nutzer einmal manuell installieren. Der Updater aktualisiert nur eine bereits installierte App. Danach läuft alles automatisch.
- **Universal-Build für macOS:** Ein Universal-Build deckt Intel und Apple Silicon ab. Dazu zwei Plattform-Einträge anlegen (`macOS Apple Silicon` und `macOS Intel`) und bei beiden dieselbe Datei und dieselbe Signatur verwenden.
- **Privaten Signatur-Schlüssel sicher aufbewahren** (z. B. als CI-Secret). Geht er verloren, können keine Updates mehr signiert und damit auch nicht mehr ausgeliefert werden.
- **Versionsnummer** muss exakt zur gebauten App passen, sonst erkennt der Updater das Update nicht korrekt.
- **Nur eine veröffentlichte Version** wird ausgeliefert: immer die neueste nach Veröffentlichungsdatum.
- **Datei-Größe:** Große Update-Pakete brauchen ausreichend hohe Upload-Limits auf dem Server (PHP `upload_max_filesize` / `post_max_size`).

## Beispiel

Version `0.2.0` für macOS und Windows veröffentlichen:

| Version | Plattform | Datei | Signatur |
|---------|----------------|------------------------------------------|-------------------|
| 0.2.0 | darwin-aarch64 | peermitly_0.2.0_universal.app.tar.gz | (Inhalt der .sig) |
| 0.2.0 | darwin-x86_64 | peermitly_0.2.0_universal.app.tar.gz | (gleiche .sig) |
| 0.2.0 | windows-x86_64 | peermitly_0.2.0_x64-setup.exe | (Inhalt der .sig) |

Der Updater nimmt auf Intel-Macs den Eintrag `darwin-x86_64`, auf Apple-Silicon-Macs `darwin-aarch64` – beide laden bei einem Universal-Build dieselbe Datei.

## Technische Notizen

- Tabelle `app_releases` mit `version`, `notes`, `platforms` (JSON: Liste aus `platform`, `path`, `signature`), `is_published`, `published_at`.
- Dateien liegen auf dem `public`-Disk im Ordner `releases`.
- Endpunkt liefert die Tauri-Updater-JSON-Struktur (`version`, `notes`, `pub_date`, `platforms`), oder `204 No Content`, wenn keine veröffentlichte Version existiert.
- Der Tauri-Endpunkt ist in `tauri.conf.json` bereits auf `https://peermitly.de/api/update/latest` gesetzt.
- Plattform-Werte sind im Enum `App\Enums\ReleasePlatform` definiert.