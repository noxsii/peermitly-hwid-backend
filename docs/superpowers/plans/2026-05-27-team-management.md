# Team Management Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Admin und Super-Admin können ihre eigenen Teams (`ownedTeams`) per UI umbenennen, erreichbar über einen neuen "Team"-Eintrag im UserMenu (Header-Dropdown).

**Architecture:** Neue Route-Gruppe `/team` mit `role:admin,super_admin`. Controller → Action → DTO Pattern. FormRequest prüft Ownership. Inertia-Page mit Deferred-Prop listet `ownedTeams` als Karten. UserMenu zeigt Eintrag per `v-if` auf user.role.

**Tech Stack:** Laravel 13, Inertia v3, Vue 3, Pest 4, shadcn-vue, Tailwind v4.

**User-Constraint:** Kein `git commit` oder `git push` während Implementation. Commit-Steps sind im Plan vermerkt, aber NICHT auszuführen.

**Spec:** `docs/superpowers/specs/2026-05-27-team-management-design.md`

---

## File Structure

**Backend (create):**
- `app/Data/Team/UpdateTeamData.php` — final readonly DTO mit `name`.
- `app/Actions/Team/UpdateTeamAction.php` — `handle(Team, UpdateTeamData): Team`.
- `app/Http/Requests/Team/UpdateTeamRequest.php` — Ownership-Auth + Name-Validation.
- `app/Http/Controllers/Team/TeamController.php` — `index()` + `update()`.
- `tests/Feature/Team/TeamControllerTest.php` — Pest Feature Tests.

**Backend (modify):**
- `routes/web.php` — Team-Routes innerhalb auth/verified, geschützt per `role:admin,super_admin`.

**Frontend (create):**
- `resources/js/pages/team/Index.vue` — Page mit `<Deferred>` Wrapper + Liste.
- `resources/js/components/team/TeamCard.vue` — Card mit Edit-Form pro Team.

**Frontend (modify):**
- `resources/js/types/team.ts` — `OwnedTeam` Interface ergänzen.
- `resources/js/types/index.ts` — Re-Export von `OwnedTeam`.
- `resources/js/layout/UserMenu.vue` — Team-Item zwischen Settings und Logout-Separator.

**Docs (create):**
- `docs/help/2026_05_27-team-verwaltung-hinzugefuegt.md` — DE Helpdoc.

---

## Phase 1: Backend

### Task 1: DTO `UpdateTeamData`

**Files:**
- Create: `app/Data/Team/UpdateTeamData.php`

- [ ] **Step 1: Create the DTO file**

```php
<?php

declare(strict_types=1);

namespace App\Data\Team;

use App\Http\Requests\Team\UpdateTeamRequest;

final readonly class UpdateTeamData
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromRequest(UpdateTeamRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
        );
    }
}
```

Note: import of `UpdateTeamRequest` will resolve once Task 3 lands. PHPStan won't run mid-task.

### Task 2: Action `UpdateTeamAction`

**Files:**
- Create: `app/Actions/Team/UpdateTeamAction.php`

- [ ] **Step 1: Create the Action**

```php
<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Data\Team\UpdateTeamData;
use App\Models\Team;

final class UpdateTeamAction
{
    public function handle(Team $team, UpdateTeamData $data): Team
    {
        $team->update(['name' => $data->name]);

        return $team->fresh();
    }
}
```

### Task 3: FormRequest `UpdateTeamRequest`

**Files:**
- Create: `app/Http/Requests/Team/UpdateTeamRequest.php`

- [ ] **Step 1: Create the FormRequest**

```php
<?php

declare(strict_types=1);

namespace App\Http\Requests\Team;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        $team = $this->route('team');

        return $team instanceof Team
            && $this->user() !== null
            && $this->user()->is($team->owner);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
```

### Task 4: Controller `TeamController`

**Files:**
- Create: `app/Http/Controllers/Team/TeamController.php`

- [ ] **Step 1: Create the Controller**

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Team;

use App\Actions\Team\UpdateTeamAction;
use App\Data\Team\UpdateTeamData;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class TeamController
{
    public function __construct(
        private readonly UpdateTeamAction $updateTeam,
    ) {}

    public function index(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('team/Index', [
            'teams' => Inertia::defer(static fn (): array => $user->ownedTeams()
                ->orderBy('name')
                ->get(['id', 'uuid', 'name'])
                ->map(static fn (Team $team): array => [
                    'id' => $team->id,
                    'uuid' => $team->uuid,
                    'name' => $team->name,
                ])
                ->all()),
        ]);
    }

    public function update(UpdateTeamRequest $request, Team $team): RedirectResponse
    {
        $this->updateTeam->handle(
            $team,
            UpdateTeamData::fromRequest($request),
        );

        return back()->with('success', 'Team updated.');
    }
}
```

### Task 5: Register Routes

**Files:**
- Modify: `routes/web.php`

- [ ] **Step 1: Add `TeamController` import and route group**

In `routes/web.php`, at the top with the other `use` imports:

```php
use App\Http\Controllers\Team\TeamController;
```

Inside the existing `Route::middleware(['auth', 'verified'])->group(function (): void { ... })` block, append:

```php
Route::middleware('role:admin,super_admin')->group(function (): void {
    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::patch('/team/{team:uuid}', [TeamController::class, 'update'])->name('team.update');
});
```

- [ ] **Step 2: Confirm routes registered**

Run: `php artisan route:list --path=team --except-vendor`
Expected: shows `GET /team` (name `team.index`) and `PATCH /team/{team}` (name `team.update`).

### Task 6: Feature Tests — happy paths

**Files:**
- Create: `tests/Feature/Team/TeamControllerTest.php`

- [ ] **Step 1: Create test file with index + update happy paths**

```php
<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('admin can view team index with owned teams', function (): void {
    $admin = User::factory()->admin()->create();
    $team = Team::factory()->ownedBy($admin)->create(['name' => 'Alpha']);

    $this->actingAs($admin)
        ->get('/team')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('team/Index')
            ->has('teams', 1)
            ->where('teams.0.uuid', $team->uuid)
            ->where('teams.0.name', 'Alpha'));
});

test('super admin can view team index', function (): void {
    $superAdmin = User::factory()->superAdmin()->create();
    Team::factory()->ownedBy($superAdmin)->create();

    $this->actingAs($superAdmin)
        ->get('/team')
        ->assertOk();
});

test('admin can rename own team', function (): void {
    $admin = User::factory()->admin()->create();
    $team = Team::factory()->ownedBy($admin)->create(['name' => 'Old Name']);

    $this->actingAs($admin)
        ->from('/team')
        ->patch("/team/{$team->uuid}", ['name' => 'New Name'])
        ->assertRedirect('/team')
        ->assertSessionHas('success');

    expect($team->fresh()->name)->toBe('New Name');
});
```

- [ ] **Step 2: Run tests**

Run: `php artisan test --compact tests/Feature/Team/TeamControllerTest.php`
Expected: 3 passing.

### Task 7: Feature Tests — authorization

**Files:**
- Modify: `tests/Feature/Team/TeamControllerTest.php`

- [ ] **Step 1: Add role + ownership tests**

Append inside the test file:

```php
test('regular user gets 403 on team index', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/team')
        ->assertForbidden();
});

test('guest cannot access team index', function (): void {
    $this->get('/team')->assertRedirect('/login');
});

test('admin cannot update foreign team', function (): void {
    $admin = User::factory()->admin()->create();
    $otherAdmin = User::factory()->admin()->create();
    $foreignTeam = Team::factory()->ownedBy($otherAdmin)->create(['name' => 'Foreign']);

    $this->actingAs($admin)
        ->patch("/team/{$foreignTeam->uuid}", ['name' => 'Hacked'])
        ->assertForbidden();

    expect($foreignTeam->fresh()->name)->toBe('Foreign');
});
```

- [ ] **Step 2: Run tests**

Run: `php artisan test --compact tests/Feature/Team/TeamControllerTest.php`
Expected: 6 passing.

### Task 8: Feature Tests — validation

**Files:**
- Modify: `tests/Feature/Team/TeamControllerTest.php`

- [ ] **Step 1: Append validation tests**

```php
test('update fails when name is missing', function (): void {
    $admin = User::factory()->admin()->create();
    $team = Team::factory()->ownedBy($admin)->create(['name' => 'Keep']);

    $this->actingAs($admin)
        ->from('/team')
        ->patch("/team/{$team->uuid}", ['name' => ''])
        ->assertSessionHasErrors('name');

    expect($team->fresh()->name)->toBe('Keep');
});

test('update fails when name exceeds 255 chars', function (): void {
    $admin = User::factory()->admin()->create();
    $team = Team::factory()->ownedBy($admin)->create(['name' => 'Keep']);

    $this->actingAs($admin)
        ->from('/team')
        ->patch("/team/{$team->uuid}", ['name' => str_repeat('a', 256)])
        ->assertSessionHasErrors('name');
});

test('route names resolve correctly', function (): void {
    expect(route('team.index', absolute: false))->toBe('/team');
});
```

- [ ] **Step 2: Run tests**

Run: `php artisan test --compact tests/Feature/Team/TeamControllerTest.php`
Expected: 9 passing.

### Task 9: Pint formatting

- [ ] **Step 1: Run Pint**

Run: `vendor/bin/pint --dirty --format agent`
Expected: no errors; any fixed files are reported.

### Task 10: Phase 1 Pause (no commit per user)

- [ ] **Step 1: Pause and hand back to user**

Backend phase complete. Per user constraint and memory rule "Pause after each phase", stop here. Report:
- Files created/modified.
- Test results (9 passing).
- Wait for user go-ahead before Phase 2.

NOTE: `git commit -m "feat: add team management backend"` is the conventional next step but MUST NOT run per user instruction.

---

## Phase 2: Frontend

### Task 11: Types — `OwnedTeam`

**Files:**
- Modify: `resources/js/types/team.ts`
- Modify: `resources/js/types/index.ts`

- [ ] **Step 1: Extend `team.ts`**

Replace contents of `resources/js/types/team.ts` with:

```ts
export interface Team {
    id: number;
    uuid: string;
    name: string;
    owner_id: number;
    created_at: string | null;
    updated_at: string | null;
}

export interface OwnedTeam {
    id: number;
    uuid: string;
    name: string;
}
```

- [ ] **Step 2: Re-export from index**

In `resources/js/types/index.ts` change the existing team export line from:

```ts
export type { Team } from "./team";
```

to:

```ts
export type { OwnedTeam, Team } from "./team";
```

### Task 12: `TeamCard` component

**Files:**
- Create: `resources/js/components/team/TeamCard.vue`

- [ ] **Step 1: Create the card component**

```vue
<script setup lang="ts">
import { Form } from "@inertiajs/vue3";
import { Loader2 } from "@lucide/vue";
import Card from "@/components/Card.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import type { OwnedTeam } from "@/types";

const props = defineProps<{
    team: OwnedTeam;
}>();
</script>

<template>
    <Card :title="props.team.name">
        <Form
            :action="`/team/${props.team.uuid}`"
            method="patch"
            class="space-y-5"
            #default="{ errors, processing }"
        >
            <div class="space-y-2">
                <Label :for="`team-name-${props.team.uuid}`">Team name</Label>
                <Input
                    :id="`team-name-${props.team.uuid}`"
                    name="name"
                    type="text"
                    :default-value="props.team.name"
                    :aria-invalid="!!errors.name || undefined"
                    autocomplete="off"
                />
                <p v-if="errors.name" class="text-destructive text-sm">
                    {{ errors.name }}
                </p>
            </div>

            <div class="pt-2">
                <Button type="submit" :disabled="processing">
                    <Loader2 v-if="processing" class="size-4 animate-spin" />
                    Save
                </Button>
            </div>
        </Form>
    </Card>
</template>
```

### Task 13: `team/Index` page

**Files:**
- Create: `resources/js/pages/team/Index.vue`

- [ ] **Step 1: Create the page**

```vue
<script setup lang="ts">
import { Deferred } from "@inertiajs/vue3";
import TeamCard from "@/components/team/TeamCard.vue";
import PageLayout from "@/layout/PageLayout.vue";
import type { OwnedTeam } from "@/types";

defineProps<{
    teams?: OwnedTeam[] | null;
}>();
</script>

<template>
    <PageLayout title="Team">
        <Deferred data="teams">
            <template #fallback>
                <div
                    class="grid grid-cols-1 items-start gap-4 lg:grid-cols-2"
                    aria-busy="true"
                >
                    <div
                        v-for="i in 2"
                        :key="i"
                        class="bg-muted/60 h-40 animate-pulse rounded-2xl"
                    />
                </div>
            </template>

            <div
                v-if="teams && teams.length > 0"
                class="grid grid-cols-1 items-start gap-4 lg:grid-cols-2"
            >
                <TeamCard
                    v-for="team in teams"
                    :key="team.uuid"
                    :team="team"
                />
            </div>

            <p v-else class="text-muted-foreground text-sm">
                You don't own any teams yet.
            </p>
        </Deferred>
    </PageLayout>
</template>
```

### Task 14: `UserMenu` Team entry

**Files:**
- Modify: `resources/js/layout/UserMenu.vue`

- [ ] **Step 1: Add `Users` icon import**

Change the lucide import line from:

```ts
import { LogOut, Settings } from "@lucide/vue";
```

to:

```ts
import { LogOut, Settings, Users } from "@lucide/vue";
```

- [ ] **Step 2: Add `canManageTeam` computed**

After the existing `initials` computed, add:

```ts
const canManageTeam = computed(() => {
    const role = user.value?.role;
    return role === "admin" || role === "super_admin";
});
```

- [ ] **Step 3: Insert Team `DropdownMenuItem`**

Inside the `<DropdownMenuContent>`, immediately after the Settings `<DropdownMenuItem>` block and before the `<DropdownMenuSeparator />` that precedes Logout, add:

```vue
<DropdownMenuItem v-if="canManageTeam" as-child>
    <Link href="/team" class="w-full">
        <Users class="size-4" />
        Team
    </Link>
</DropdownMenuItem>
```

The final menu order: Label → Separator → Settings → Team (admin/super_admin only) → Separator → Logout.

### Task 15: Frontend build sanity check

- [ ] **Step 1: Type-check / build**

Run: `bun run build`
Expected: build succeeds, no TS errors. If the dev server is preferred, run `bun run dev` instead and watch for HMR errors.

### Task 16: Manual UI check

- [ ] **Step 1: Verify in browser**

Steps:
1. Open the project URL (`https://permitly.test` or via Herd).
2. Log in as a user with role `admin` (or seed/promote one).
3. Open the user-menu (avatar top right) → confirm "Team" entry visible between Settings and Logout.
4. Click Team → page `/team` opens, skeleton shown, then list of owned teams renders.
5. Rename one team → spinner shown in Save button → success flash, name updated in card title.
6. Log out, log in as a regular user → confirm Team entry absent from UserMenu.
7. As regular user, navigate to `/team` directly → confirm 403 error page.

If you cannot run the browser yourself, say so explicitly (per CLAUDE.md) and stop after `bun run build`.

### Task 17: Phase 2 Pause (no commit per user)

- [ ] **Step 1: Pause and hand back to user**

Frontend phase complete. Report:
- Files created/modified.
- Build success.
- Manual-check result.
- Wait for user go-ahead before Phase 3.

NOTE: `git commit -m "feat: add team management UI"` MUST NOT run per user instruction.

---

## Phase 3: Dokumentation

### Task 18: Help doc (DE)

**Files:**
- Create: `docs/help/2026_05_27-team-verwaltung-hinzugefuegt.md`

- [ ] **Step 1: Create the help doc**

```markdown
# Team-Verwaltung hinzugefügt

## Datum

2026-05-27

## Bereich

Team, Benutzermenü, Einstellungen

## Kurzbeschreibung

Administratoren können den Namen ihrer eigenen Teams jetzt direkt in der Anwendung ändern. Ein neuer Menüpunkt "Team" wurde im Benutzermenü oben rechts ergänzt.

## Was ist neu?

- Neuer Menüpunkt "Team" im Benutzermenü (oben rechts).
- Neue Seite `/team`, auf der alle eigenen Teams als Karten angezeigt werden.
- Jede Karte enthält ein Eingabefeld für den Teamnamen und einen Speichern-Button.

## Warum wurde das geändert?

Bislang konnte der Teamname nur durch einen Super-Administrator über das Admin-Panel geändert werden. Mit der neuen Seite können Team-Eigentümer ihre Teams selbstständig umbenennen.

## Wie funktioniert es?

1. Auf das eigene Profilbild oben rechts klicken, um das Benutzermenü zu öffnen.
2. Auf "Team" klicken (nur sichtbar für Administratoren und Super-Administratoren).
3. Auf der Team-Seite wird für jedes eigene Team eine Karte angezeigt.
4. Den gewünschten neuen Namen ins Eingabefeld eintragen.
5. Auf "Save" klicken. Der Name wird sofort gespeichert und in der Karten-Überschrift aktualisiert.

## Betroffene Bereiche

- Benutzermenü im Header (`UserMenu`).
- Neue Seite `/team`.
- Datenbanktabelle `teams` (Spalte `name`).

## Wichtige Hinweise

- Der Menüpunkt ist nur für Benutzer mit der Rolle "Admin" oder "Super-Admin" sichtbar.
- Es können ausschließlich Teams umbenannt werden, deren Eigentümer der eingeloggte Benutzer ist.
- Der Teamname darf maximal 255 Zeichen lang sein und darf nicht leer sein.
- Andere Teameinstellungen (Mitglieder, Besitzer, Erstellen, Löschen) sind in dieser Änderung nicht enthalten.

## Beispiel

Ein Admin besitzt zwei Teams: "Alpha" und "Beta". Nach dem Öffnen von `/team` sieht er beide als Karten. Er ändert "Alpha" zu "Acme GmbH", klickt auf Save und der neue Name erscheint sofort in der Karten-Überschrift sowie überall, wo der Teamname angezeigt wird.

## Technische Notizen

- Routes: `GET /team` (`team.index`) und `PATCH /team/{team:uuid}` (`team.update`).
- Geschützt per Middleware `role:admin,super_admin` und zusätzlich per FormRequest-Ownership-Check.
- Persistenz erfolgt über `App\Actions\Team\UpdateTeamAction` mit DTO `App\Data\Team\UpdateTeamData`.
```

### Task 19: Phase 3 Pause (no commit per user)

- [ ] **Step 1: Final hand-back**

All three phases complete. Report:
- Backend tests passing.
- Frontend build clean + manual check done (or noted as not possible).
- Help doc written.

NOTE: Final `git commit` MUST NOT run per user instruction. User decides when to commit.

---

## Self-Review

**Spec coverage:**
- Routes/middleware → Task 5. ✓
- Controller → Task 4. ✓
- Action → Task 2. ✓
- DTO → Task 1. ✓
- FormRequest (auth + rules) → Task 3. ✓
- Doppelter Auth-Layer → Routes (Task 5) + FormRequest (Task 3) + scoped `ownedTeams()` in controller (Task 4). ✓
- Page `team/Index.vue` → Task 13. ✓
- UserMenu Eintrag → Task 14. ✓
- Types (`OwnedTeam`) → Task 11. ✓
- Tests (6 spec scenarios) → Tasks 6–8 (9 tests in total: index admin, index super_admin, update happy, regular user 403, guest, foreign 403, validation x2, route names). ✓
- Doku → Task 18. ✓
- Pint → Task 9. ✓
- Phasen-Pausen → Tasks 10, 17, 19. ✓

**Placeholder scan:** no TBD/TODO/etc. ✓

**Type consistency:** `UpdateTeamData::name` is `string`, used identically in Action, Controller, FormRequest. `OwnedTeam` interface fields match controller payload (`id`, `uuid`, `name`). Route param `{team:uuid}` matches `Team::getRouteKeyName()`. ✓