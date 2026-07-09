# Next.js-Sites: neue Anleitung im Guide

## Datum

2026-07-09

## Bereich

Dokumentation (Guide), Sites

## Kurzbeschreibung

Die Peermitly-App kann jetzt Next.js-Projekte erstellen und über eine `.peer`-Domain ausliefern (Pro-Feature). Dafür gibt es eine neue Guide-Seite unter `/guide/nextjs`.

## Was ist neu?

- **Neue Guide-Seite "Next.js"** in der Navigation unter **Sites** (mit Pro-Kennzeichnung), erreichbar unter `https://peermitly.de/guide/nextjs`.
- Die Seite ist in der `sitemap.xml` eingetragen.

## Warum wurde das geändert?

Next.js wurde als neues Framework für den Site-Wizard der App hinzugefügt. Nutzer brauchen eine Anleitung, wie sie eine Next.js-Site erstellen und was dabei automatisch konfiguriert wird.

## Wie funktioniert es?

Der Guide beschreibt den Ablauf:

1. **Voraussetzungen:** Eine aktive Node-Version und laufende Dienste (nginx, DNS).
2. **Erstellen:** Im Site-Wizard **Next.js** wählen. Peermitly führt den offiziellen Scaffolder `npx create-next-app@latest` aus und installiert die Abhängigkeiten.
3. **Optionen:** TypeScript, Tailwind CSS, ESLint, App Router und Turbopack sind standardmäßig aktiviert; src-Directory ist optional. Ein Git-Repository kann direkt mit angelegt werden.
4. **Automatische Konfiguration:** Der Dev-Server wird auf den gewählten Port festgelegt und die `.peer`-Domain wird in `allowedDevOrigins` der `next.config` eingetragen — keine manuelle Konfiguration nötig.
5. **Starten:** `npm run dev` im Projekt ausführen, dann `https://<name>.peer` öffnen. HTTPS und Fast Refresh laufen direkt über die Domain.

## Betroffene Bereiche

- Guide-Navigation (`config/docs.php`, Sektion "Sites" → "Next.js")
- Neue Guide-Seite `resources/js/docs/nextjs.md`
- `public/sitemap.xml`
- Profiler-Guide: Next.js in der Liste der Node-Sites ergänzt

## Wichtige Hinweise

- Next.js-Sites sind ein **Pro-Feature**.
- Der Dev-Server muss laufen, damit die Site erreichbar ist; sonst zeigt Peermitly eine Offline-Seite.
- Läuft der Dev-Server nicht auf dem festgelegten Port, greift der nginx-Proxy ins Leere — der Port wird beim Erstellen fest vergeben.

## Beispiel

Ein Nutzer erstellt im Site-Wizard eine Site "shop" mit Next.js, lässt TypeScript, Tailwind und App Router aktiviert und übernimmt den vorgeschlagenen Port 3000. Nach dem Scaffolding führt er `npm run dev` aus und öffnet `https://shop.peer` — die App lädt über HTTPS, Änderungen erscheinen sofort per Fast Refresh.

## Technische Notizen

- Neuer Slug `nextjs` in `config/docs.php` (Sektion Sites, `pro => true`); der bestehende Sitemap-Test deckt den Slug automatisch mit ab.
- Neuer Feature-Test "the nextjs guide slug renders" in `tests/Feature/DocsTest.php`.
- App-Seite (Tauri): Dev-Script `next dev -H 0.0.0.0 -p <port> [--turbopack]`, `allowedDevOrigins: ['<name>.peer']` wird in `next.config.ts/.mjs/.js` injiziert.
- Noch kein Screenshot im Guide — `public/images/screenshots/nextjs.png` kann später ergänzt werden.