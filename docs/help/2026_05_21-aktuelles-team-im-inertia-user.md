# Aktuelles Team am Inertia-User verfügbar

## Datum

2026-05-21

## Bereich

Benutzer, Frontend-Datenfluss

## Kurzbeschreibung

Das aktuell ausgewählte Team eines Benutzers steht im Frontend jetzt direkt am `auth.user` zur Verfügung. Damit lassen sich Team-Name, Owner-Status etc. ohne separate API-Aufrufe in jeder Inertia-Seite anzeigen.

## Was ist neu?

- Auf jeder Inertia-Seite enthält `auth.user.current_team` die Daten des aktuellen Teams des eingeloggten Benutzers (oder `null`, falls kein Team gesetzt ist).
- Neue TypeScript-Typdefinition `Team` in `resources/js/types/team.ts`, ergänzt um Feld `current_team: Team | null` im `User`-Typ.

## Warum wurde das geändert?

In der Anwendung ist `Team` der zentrale Mandant. Ohne das aktuelle Team im Frontend müsste jede Seite den Team-Kontext separat anfragen oder via Props weiterreichen. Über das `auth.user` ist es global ohne Mehraufwand verfügbar.

## Wie funktioniert es?

1. `HandleInertiaRequests::share()` lädt beim Bereitstellen von `auth.user` die `currentTeam`-Relation per Eager-Loading.
2. Laravel serialisiert die Relation als `current_team` im JSON.
3. Im Frontend kann das Team an jeder Stelle gelesen werden:

```ts
import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';

const page = usePage<PageProps>();
const team = page.props.auth.user?.current_team ?? null;
```

## Betroffene Bereiche

- `app/Http/Middleware/HandleInertiaRequests.php`
- `resources/js/types/team.ts` (neu)
- `resources/js/types/user.ts` (Feld `current_team` hinzugefügt)
- `resources/js/types/index.ts` (Re-Export `Team`)

## Wichtige Hinweise

- Hat ein Benutzer noch kein Team gewählt (`current_team_id IS NULL`), ist `auth.user.current_team` `null`.
- Die Relation wird in jedem Request geladen — pro Request ein zusätzlicher Datenbank-Query. Für besonders heiße Pfade kann später Caching nachgerüstet werden.

## Beispiel

Ein Header zeigt den Team-Namen an, falls eingeloggt:

```vue
<template>
    <span v-if="team">Team: {{ team.name }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';

const page = usePage<PageProps>();
const team = computed(() => page.props.auth.user?.current_team ?? null);
</script>
```

## Technische Notizen

- Eager-Loading: `$request->user()?->load('currentTeam')` in der Closure.
- Serialisierung: Laravel snake-cased die Relation (`currentTeam` → `current_team`) automatisch.
- Kein DTO, kein Helper-Method-Wrapper – die Eloquent-Serialisierung erzeugt direkt die erwartete Form.