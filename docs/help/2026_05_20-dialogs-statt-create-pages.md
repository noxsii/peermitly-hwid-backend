# Erstellen und Bearbeiten als Dialog (statt eigener Seite)

## Datum

2026-05-20

## Bereich

UI-Konvention, Lizenzkeys, Lizenzkey-Typen

## Kurzbeschreibung

Statt eigener Seiten für "New X" und "Edit X" laufen Anlage- und
Bearbeitungs-Flows nun über Dialoge auf der jeweiligen Index-Seite.

## Was ist neu?

- "New key", "Bulk create", Edit eines Lizenzkey-Typs und Revoke/Restore
  öffnen Dialoge, keine eigenen Seiten mehr.
- Alle Dialog-Komponenten liegen in `resources/js/components/dialogs/`.
- Bestätigungs-Dialoge (z.B. Löschen) nutzen einen gemeinsamen
  `ConfirmDialog`-Wrapper.

## Warum wurde das geändert?

Separate `/create`- und `/edit`-Seiten sahen mit nur einem Formular sehr
leer aus und zwangen den Nutzer zu unnötigen Seitenwechseln. Die
Dialog-Variante hält den Nutzer im Kontext der Liste.

## Wie funktioniert es?

- Index-Seite hat ref-Status (`createOpen`, `bulkOpen`, etc.).
- Buttons setzen den Status auf `true`.
- Dialog-Komponente ist controlled via `v-model:open`.
- Nach erfolgreichem Submit schließt sich der Dialog und die Liste lädt
  über Inertia-Deferred-Props neu.

## Betroffene Bereiche

- `/license-keys` — "New key" und "Bulk create" als Dialog.
- `/license-keys/types` — Bearbeiten und Löschen als Dialog.
- `/settings` — "New API Token" und Token-Anzeige als Dialog.
- Bestätigung beim Löschen — über `ConfirmDialog`.

## Wichtige Hinweise

- Native `confirm()` und `alert()` sind in der gesamten UI verboten.
  Bestätigungen laufen über `ConfirmDialog`.
- Dialoge nutzen die shadcn-vue `Dialog`-Komponente
  (nicht `AlertDialog`, außer für ConfirmDialog selbst).
- Form-Daten werden über Inertia `useForm` mit `onSuccess` Callback
  abgewickelt — Dialog schließt sich automatisch nach erfolgreichem
  Submit.

## Beispiel

```vue
<Button @click="createOpen = true">New key</Button>

<CreateLicenseKeyDialog
    v-model:open="createOpen"
    :types="types?.data ?? []"
    :products="products?.data ?? []"
    :customers="customers?.data ?? []"
/>
```

## Technische Notizen

Entfernte Routen und Seiten:

- `GET /license-keys/create` (Controller-Methode `create`) — weg
- `GET /license-keys/bulk` (Controller-Methode `bulkCreate`) — weg
- `GET /license-keys/types/create` (Controller-Methode `create`) — weg
- `GET /license-keys/types/{uuid}/edit` (Controller-Methode `edit`) — weg
- Vue-Pages `pages/license-keys/Create.vue`,
  `pages/license-keys/BulkCreate.vue`, `pages/license-keys/types/Create.vue`,
  `pages/license-keys/types/Edit.vue` — gelöscht

Die `store`-, `update`- und `destroy`-Endpoints bleiben und redirecten
mit `back()`, sodass der Index nach erfolgreichem Submit die aktualisierte
Liste anzeigt.
