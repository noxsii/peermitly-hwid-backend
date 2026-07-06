# IDE-Integration: Sites direkt im Editor öffnen

## Datum

2026-07-06

## Bereich

Sites, Einstellungen, Dokumentation

## Kurzbeschreibung

Jede Site kann jetzt mit einem Klick im bevorzugten Editor geöffnet werden. Der Nutzer wählt seine IDE einmalig in den Einstellungen, danach zeigt jede Site in der Sites-Liste einen "Open in …"-Button.

## Was ist neu?

- Neuer Button **"Open in …"** (z. B. "Open in VS Code") an jeder Site-Zeile, neben den bestehenden Buttons zum Öffnen im Browser und im Finder.
- Die Auswahl **Preferred IDE** in **Settings → General** ist jetzt aktiv und steuert diesen Button.
- Unterstützte Editoren: VS Code, Cursor, PhpStorm, WebStorm, IntelliJ IDEA, Sublime Text, Zed, Nova, Neovim, Fleet, Windsurf.
- Neuer öffentlicher Hilfe-Artikel **"IDE integration"** im Guide unter der Sektion **Tools**.

## Warum wurde das geändert?

Bisher musste man den Projektordner manuell im Finder suchen oder per Terminal öffnen, um an einer Site zu arbeiten. Der direkte Sprung aus Peermitly in den Editor spart diesen Umweg.

## Wie funktioniert es?

1. **Settings** öffnen, Tab **General**.
2. Unter **Preferred IDE** den gewünschten Editor auswählen. Die Auswahl wird automatisch gespeichert.
3. Zur Seite **Sites** wechseln. Jede Site zeigt nun den Button **Open in …** mit dem Namen des gewählten Editors.
4. Klick auf den Button öffnet den Projektordner der Site direkt im Editor.

Sonderfall Neovim: Da Neovim ein Terminal-Editor ist, öffnet Peermitly ein neues Terminal-Fenster und startet dort `nvim` im Projektordner.

## Betroffene Bereiche

- Sites-Liste (Site-Zeile, neuer Button)
- Settings → General (Preferred IDE)
- Öffentliche Dokumentation: neuer Guide `/guide/ide` unter "Tools"

## Wichtige Hinweise

- Der Button erscheint **nur**, wenn in den Einstellungen eine IDE ausgewählt wurde. Ohne Auswahl ist er ausgeblendet.
- Die IDE-Auswahl gilt global für alle Sites, nicht pro Site.
- **Free-Feature**: für alle Nutzer verfügbar, keine Pro-Lizenz nötig.
- Fehlermeldungen erscheinen direkt neben dem Button:
  - Editor nicht installiert: "… could not be opened. Is it installed?"
  - Projektordner existiert nicht mehr: "Project folder no longer exists."

## Beispiel

Ein Nutzer arbeitet mit PhpStorm. Er wählt in Settings → General "PhpStorm" als Preferred IDE. In der Sites-Liste zeigt seine Laravel-Site nun "Open in PhpStorm". Ein Klick öffnet das Projekt sofort in PhpStorm.

## Technische Notizen

- Umsetzung in der macOS-App (Tauri): Rust-Command `ide_open` (`src-tauri/src/commands/ide.rs`), startet Editoren auf macOS via `open -a "<App-Name>"`, Neovim via AppleScript im Terminal.
- IDE-Liste zentral in `src/lib/ides.ts`, Frontend-Service `src/services/ide.ts`.
- Button in `src/components/dashboard/sites/SiteRow.vue`, IDE-Auswahl in `src/views/dashboard/Settings.vue`.
- Backend (dieses Repo): neuer Guide `resources/js/docs/ide.md`, Navigationseintrag in `config/docs.php` (Sektion "Tools", Slug `ide`).
