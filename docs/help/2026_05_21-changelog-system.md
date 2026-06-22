# Changelog im Frontend und Filament

## Datum

2026-05-21

## Bereich

Changelog, Sidebar, Filament-Admin, Inhalte

## Kurzbeschreibung

Peermitly hat jetzt einen öffentlich (nach Login) sichtbaren Changelog-Bereich.
Einträge werden im Filament-Admin per WYSIWYG-Editor gepflegt und sind
auf `/changelog` aufgelistet — sortiert nach Veröffentlichungsdatum.

## Was ist neu?

- Neuer Sidebar-Eintrag **Changelog** unten links (Sekundär-Navigation).
- Öffentliche (auth-geschützte) Seite `/changelog` mit chronologischer Liste
  aller veröffentlichten Einträge.
- Pagination (10 Einträge pro Seite) am unteren Ende der Liste.
- Pflege im Filament-Admin unter **Content → Changelog**:
  - Titel (Pflichtfeld)
  - Version (optional, z.B. „v1.2.0")
  - Veröffentlichungsdatum (Datum/Zeit-Picker, leer = Entwurf)
  - Inhalt (Rich-Text-Editor mit Formatierungen, Listen, Code, Links, …)
- Tabelle im Admin zeigt Titel, Version, Veröffentlicht-Status und Datum.

## Warum wurde das geändert?

Bisher gab es keine Möglichkeit, Releases oder Änderungen am Produkt für
eingeloggte Nutzer sichtbar zu kommunizieren. Mit dem Changelog können
Admins zentral und ohne Deploy neue Versionen ankündigen.

## Wie funktioniert es?

1. Admin öffnet Filament unter `/admin/changelogs` und klickt auf **New
   Changelog Entry**.
2. Titel, Version und Inhalt eintragen. Inhalt darf reiches HTML enthalten
   (Listen, Überschriften, Code, Links).
3. Wenn der Eintrag live gehen soll: **Published at** auf das gewünschte
   Datum setzen.
4. Speichern. Eingeloggte Nutzer sehen den Eintrag sofort auf `/changelog`.
5. Entwurf bleiben Einträge so lange, wie **Published at** leer ist.

## Betroffene Bereiche

- Migration `2026_05_21_120000_create_changelogs_table.php`
- Model `App\Models\Changelog`
- Factory `Database\Factories\ChangelogFactory` (inkl. `unpublished()` State)
- Controller `App\Http\Controllers\ChangelogController`
- API-Resource `App\Http\Resources\ChangelogResource`
- Route `GET /changelog` (Name: `changelog.index`)
- Inertia-Seite `resources/js/pages/changelog/Index.vue`
- Type `resources/js/types/changelog.ts`
- Sidebar (`AppSidebar.vue`) — neuer Sekundär-Eintrag „Changelog"
- Filament-Resource `App\Filament\Resources\Changelogs\ChangelogResource`
  inkl. `ChangelogForm`, `ChangelogsTable`, sowie List/Create/Edit-Pages
- Pest-Tests `tests/Feature/Changelog/ChangelogPageTest.php`

## Wichtige Hinweise

- **Entwürfe**: Einträge ohne `published_at` erscheinen nicht im Frontend.
  Der Admin sieht sie weiterhin in Filament (Spalte „Published" zeigt
  ein Häkchen oder Kreuz).
- **Reihenfolge**: Im Frontend werden Einträge absteigend nach
  `published_at` sortiert (neueste oben).
- **Rich-Content**: Der WYSIWYG-Editor speichert HTML. Im Frontend wird das
  HTML über `v-html` gerendert. Da nur vertrauenswürdige Admins schreiben
  können, ist das hier vertretbar — nicht für Nutzereingaben verwenden!
- **Berechtigungen**: Aktuell kann jeder Filament-Admin Changelog-Einträge
  erstellen und löschen. Falls künftig nur bestimmte Rollen pflegen
  sollen, in `ChangelogResource` Policies/Visibility ergänzen.
- **Pagination**: 10 Einträge pro Seite. Bei Bedarf in
  `ChangelogController::index` per `paginate(...)` anpassen.

## Beispiel

> Peermitly bringt Version 1.4 raus. Admin öffnet `/admin/changelogs`,
> klickt **New Changelog Entry**, trägt Titel „API tokens & Customer CRUD",
> Version „v1.4.0", Inhalt als formatierte Liste mit den neuen Features
> und setzt **Published at** auf den aktuellen Tag. Speichern.
> Eingeloggte Nutzer sehen beim nächsten Aufruf von `/changelog` den
> Eintrag ganz oben.

## Technische Notizen

- `Changelog` ist global (kein `team_id`). Alle Nutzer sehen denselben
  Changelog — passend zur Annahme „ein Produkt, ein Release-Stream".
- Inertia-Daten werden via `Inertia::defer()` nachgeladen; das Frontend
  zeigt während des Ladens Skeleton-Karten.
- Pagination wird über die wiederverwendbare `DataTablePagination`-Komponente
  (`@/components/table`) gerendert, dieselbe wie in den anderen Listen.
- Filament-Resource liegt im Navigation-Group „Content", damit künftige
  Inhalts-Resourcen (z.B. FAQs, Release-Notizen) dort gruppiert werden
  können.
- Tests prüfen: Auth-Pflicht, Render der Inertia-Komponente, Routenname
  und dass Entwürfe (`published_at = null`) nicht im veröffentlichten
  Listing erscheinen.
