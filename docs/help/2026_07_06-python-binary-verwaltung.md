# Python als verwaltetes Binary hinzugefügt

## Datum

2026-07-06

## Bereich

Binaries, Dokumentation

## Kurzbeschreibung

Peermitly verwaltet jetzt neben PHP und Node auch Python. Dazu gibt es eine neue Dokumentationsseite unter „Binaries → Python versions".

## Was ist neu?

- Python kann direkt in der App installiert, aktiviert, aktualisiert und entfernt werden — über den neuen Bereich **Python** unter **Binaries**.
- Mehrere Python-Versionen können parallel installiert sein; die aktive Version wird per Klick gewechselt.
- pip wird automatisch mit jeder Python-Version mitgeliefert.
- Neue Doku-Seite `python.md` in der Hilfe, verlinkt in der Sidebar unter „Binaries".
- Neuer Troubleshooting-Artikel „Shell is not using the Homebrew Python" (`homebrew-python-path.md`) für den Fall, dass das Terminal eine andere Python-Version nutzt als die App (z. B. wegen pyenv, conda oder dem macOS-System-Python).

## Warum wurde das geändert?

Peermitly soll alle gängigen Entwicklungs-Stacks abdecken. Python gehört zu den meistgenutzten Sprachen und war bisher nicht über die App verwaltbar.

## Wie funktioniert es?

1. In der App den Bereich **Binaries → Python** öffnen.
2. Gewünschte Version in der Liste suchen und auf **Install** klicken.
3. Nach der Installation auf **Activate** klicken — `python3` und `pip3` im Terminal nutzen dann diese Version.
4. Prüfen mit `python3 --version` und `pip3 --version`.
5. Updates erscheinen als „Update available" und werden per Klick eingespielt; nicht mehr benötigte Versionen lassen sich per **Remove** entfernen.

## Betroffene Bereiche

- App: neuer Bereich „Python" unter „Binaries"
- Hilfe/Doku: neue Seite „Python versions" (`resources/js/docs/python.md`)
- Hilfe/Doku: neuer Troubleshooting-Artikel (`resources/js/docs/homebrew-python-path.md`)
- Sidebar-Navigation der Doku (`config/docs.php`)

## Wichtige Hinweise

- Es ist immer nur eine Python-Version systemweit aktiv.
- Versionsmanager wie **pyenv** oder **conda** können die aktive Version im Terminal überschreiben — deren Init-Zeilen in der Shell-Konfiguration ggf. deaktivieren.
- Wird die aktive Version entfernt, danach eine andere Version aktivieren, damit `python3` weiter funktioniert.

## Beispiel

Ein Projekt benötigt Python 3.11, ein neues Projekt die aktuelle Version: Beide Versionen installieren und je nach Projekt die passende aktivieren. Virtuelle Umgebungen (`python3 -m venv .venv`) bleiben an die Version gebunden, mit der sie erstellt wurden.

## Technische Notizen

- Slug `python` in `config/docs.php` unter der Sektion „Binaries" registriert.
- Markdown-Datei liegt wie alle Doku-Seiten unter `resources/js/docs/{slug}.md`.