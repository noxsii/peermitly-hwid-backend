# News — Premium Frontend Design

**Ziel:** News-Übersicht + Detailseite sehen richtig geil aus. Jeder Code-Block, jede
Headline, Blockquote, Tabelle, Liste im Peermitly-Style. Eigene News-Komponenten. Eine
fette Demo-News zum Vorzeigen.

**Entscheidungen (bestätigt):** RichEditor bleibt im Admin (HTML-Content). Kein
Markdown, kein Shiki, keine neuen Dependencies. „Geil" kommt aus Premium-CSS + eigenen
Komponenten + Copy-Buttons auf Code-Blocks.

---

## 1. Eigene News-Komponenten (`resources/js/components/news/`)

- **`NewsPageShell.vue`** — gemeinsamer Rahmen (Header mit Logo + Log-in, Footer) für
  beide News-Seiten. Slot für Inhalt. Entfernt Duplikat aus Index/Show.
- **`NewsCard.vue`** — eine Karte der Übersicht: Bild (oder Gradient-Fallback),
  Datum, Titel, Description (line-clamp), Hover-Lift + Bild-Zoom. Props: `article`.
- **`NewsHero.vue`** — Detail-Kopf: Zurück-Link, Datum, großer Gradient-Titel,
  Lead-Description, Hero-Bild (nur wenn vorhanden).
- **`NewsContent.vue`** — rendert `v-html` des RichEditor-HTML in `.news-prose`;
  `onMounted` reichert an:
  - Copy-Button auf jedem `<pre>` (Pattern aus `DocsContent.vue`, eigenständig)
  - Sprach-/Label-Bar oben am Code-Block (optional, dezent)
  - Bild-Zoom-Overlay (Klick → Lightbox, Esc schließt)
  Skeleton nicht nötig (Content kommt synchron mit der Seite).

Index.vue + Show.vue werden auf diese Komponenten umgebaut (dünn, nur Daten + Meta).

## 2. Premium-Styling (`resources/css/app.css`)

Neuer `@layer components`-Block **`.news-prose`** — visuelle Sprache wie `docs-prose`,
aber für RichEditor-HTML (nutzt `<pre>`, nicht `pre.shiki`):

- **Headlines** h1/h2/h3: Gradient-h1, h2 mit Primary-Underline-Verlauf, klare Hierarchie.
- **Absätze/Lead:** erster Absatz größer/muted.
- **Listen:** Custom Primary-Bullets (ul), Primary-Marker (ol).
- **Inline-Code:** Primary-getönte Pille.
- **Code-Block `<pre>`:** dunkle Karte, abgerundet, Border, Top-Bar mit Ampel-Dots +
  Copy-Button rechts, mono, horizontal scrollbar bei Overflow. Theme-aware (light/dark).
- **Blockquote:** Primary-Left-Border, getönter Hintergrund, abgerundet.
- **Tabellen:** gerundete Border, Header-Tönung, Zeilen-Hover.
- **Links:** animierter Underline-Grow (wie docs-prose).
- **`<mark>` (highlight):** Primary-Tönung. **Bilder:** gerundet, zoom-Cursor.

`tailwindcss-development`-Skill wird bei der Umsetzung aktiviert.

## 3. Fette Demo-News (Seeder)

- **`database/factories/NewsFactory.php`** — neuer State `showcase()`: setzt reichhaltigen
  RichEditor-HTML-Content (h2/h3, Lead, Bullet- + Nummern-Liste, Inline-Code, ein
  `<pre><code>`-Block mit Beispiel-Code, Blockquote, Tabelle, Link, `<strong>`, `<mark>`).
- **`database/seeders/NewsSeeder.php`** — erstellt 1 fette veröffentlichte Showcase-News
  („HWID Spoofer v2 — was neu ist") + 2 kürzere veröffentlichte + 1 Draft. Idempotent
  (`if (News::query()->exists()) return;`), Muster wie `ChangelogSeeder`.
- In `DatabaseSeeder` registrieren (neben ChangelogSeeder, falls dort registriert).

## 4. Betroffene Dateien

Neu: 4 Vue-Komponenten (`components/news/`), `NewsSeeder`. Geändert:
`pages/news/Index.vue`, `pages/news/Show.vue`, `resources/css/app.css`,
`database/factories/NewsFactory.php`, `database/seeders/DatabaseSeeder.php`.
Admin/Backend/Model/Routes unverändert.

## 5. Verifikation

- `php artisan test --compact --filter=News` → weiterhin 18/18 grün.
- `php artisan db:seed --class=NewsSeeder` → Demo-News angelegt.
- `npm run build` → grün, keine Fehler.
- Sichtprüfung `/news` + `/news/<slug>` (light + dark).
- Kein Commit (Policy) — alles im Working Tree.

## 6. Bewusst weggelassen (YAGNI)

- Kein Syntax-Highlighting (RichEditor liefert keine Tokens; User-Entscheidung).
- Kein Markdown-Pipeline, kein Shiki-Runtime, keine neuen npm-Pakete.
- Keine Reading-Time/Autor/Tags — nicht angefragt.