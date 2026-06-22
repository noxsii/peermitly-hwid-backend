# Kundenverwaltung hinzugefügt

## Datum

2026-05-21

## Bereich

Kunden, Lizenzkeys, Sidebar

## Kurzbeschreibung

Es gibt jetzt eine eigene Verwaltung für Kunden. Über die neue Sidebar-Seite
**Customers** lassen sich Kunden anlegen, bearbeiten und löschen. Die Optik
und Bedienung entspricht den bestehenden Seiten **Products** und **Key Types**.

## Was ist neu?

- Neuer Sidebar-Eintrag **Customers** (Icon: Users) direkt unter **License Keys**.
- Übersichtsseite unter `/license-keys/customers` mit Tabelle aller Kunden des
  aktuellen Teams.
- Inline-Formular auf der rechten Seite zum Anlegen neuer Kunden.
- Bearbeiten und Löschen pro Zeile über Dialoge (Edit-Dialog, Bestätigungsdialog
  für das Löschen).
- Pflichtfeld: **Email**. Optional: **Name**, **Company**, **Metadata** (JSON).
- Anzahl der zugewiesenen Lizenzkeys pro Kunde wird in der Tabelle angezeigt.

## Warum wurde das geändert?

Bisher konnten Kunden nur mittelbar (z.B. beim Anlegen eines Lizenzkeys) an
Datensätzen hängen, es gab aber keine dedizierte Verwaltung. Für Support,
Korrekturen und sauberes Stammdaten-Management muss jeder Kunde eigenständig
pflegbar sein.

## Wie funktioniert es?

1. **Anlegen**: Sidebar **Customers** öffnen → rechtes Formular ausfüllen
   (mindestens Email) → **Create customer** klicken.
2. **Bearbeiten**: In der Tabelle auf das Stift-Symbol klicken → Dialog
   öffnet sich mit den aktuellen Daten → ändern → **Save changes**.
3. **Löschen**: Auf das Mülltonnen-Symbol klicken → Bestätigungsdialog → **Delete**.
4. **Metadata**: Beliebige JSON-Struktur (z.B. `{ "plan": "enterprise", "notes": "VIP" }`).
   Wird beim Speichern auf gültiges JSON-Objekt geprüft.

## Betroffene Bereiche

- Sidebar (`AppSidebar.vue`)
- Neue Route-Gruppe `/license-keys/customers/*` in `routes/license-keys.php`
- Neuer Controller `App\Http\Controllers\LicenseKeys\CustomerController`
- Neue Form Requests `StoreCustomerRequest`, `UpdateCustomerRequest`
- Neue API-Resource `CustomerResource`
- Neue Inertia-Seite `resources/js/pages/license-keys/customers/Index.vue`
- Neue Komponenten unter `resources/js/components/license-keys/`
  (`CustomerForm.vue`, `CustomerTable.vue`, `CustomerRowActions.vue`,
  `columns/customerColumns.ts`)
- Neue Dialoge unter `resources/js/components/dialogs/`
  (`CreateCustomerDialog.vue`, `EditCustomerDialog.vue`)
- Pest-Tests unter `tests/Feature/LicenseKeys/Customers/`

## Wichtige Hinweise

- **Team-Scoping**: Es werden ausschließlich Kunden des aktuellen Teams
  (`auth()->user()->current_team_id`) angezeigt und verwaltet. Versucht
  jemand, einen Kunden eines anderen Teams per UUID zu bearbeiten oder zu
  löschen, antwortet die Anwendung mit **404**.
- **Email-Unique pro Team**: Innerhalb eines Teams ist jede Email-Adresse nur
  einmal erlaubt. Über Teams hinweg darf dieselbe Email mehrfach existieren.
- **Metadata**: Muss ein JSON-Objekt sein (kein Array, kein primitiver Wert).
  Leeres Feld speichert `null`.
- **Lizenzkeys bleiben erhalten**: Beim Löschen eines Kunden bleiben dessen
  Lizenzkeys bestehen — sie zeigen anschließend auf einen nicht mehr
  existierenden Kunden (`customer_id` wird nicht automatisch geleert).

## Beispiel

> Ada Lovelace soll als Kundin angelegt werden. Admin öffnet die Sidebar,
> klickt **Customers**, trägt `ada@example.com` als Email, `Ada Lovelace`
> als Name und `Analytical Engines Ltd.` als Company ein, optional
> `{ "plan": "enterprise" }` als Metadata, und klickt **Create customer**.
> Ada erscheint sofort in der Tabelle links — danach kann sie beim Anlegen
> von Lizenzkeys ausgewählt werden.

## Technische Notizen

- Routing-Pattern: `Route::patch('/customers/{customer:uuid}', ...)` — UUID
  als implizites Model Binding, wie bei Products und Key Types.
- Team-Scoping im Controller: bei `store` per `current_team_id`, bei
  `update`/`destroy` per `abort_unless($customer->team_id === ...)`.
- Eindeutigkeit der Email pro Team: `Rule::unique('customers', 'email')->where('team_id', $teamId)`.
- Inertia-Defer: Die Customer-Tabelle wird via `Inertia::defer()` nachgeladen,
  damit der initiale Page-Render schnell bleibt. Frontend zeigt während des
  Ladens Skeleton-Zeilen.
- Tests: `CustomerIndexPageTest.php` deckt Auth, Render und Team-Scoping ab;
  `CustomerCrudTest.php` testet `store/update/destroy` inkl. Email-Validierung
  und Cross-Team-404.
