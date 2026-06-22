# Team Management — Design

## Datum
2026-05-27

## Ziel
Admin (und Super-Admin) sollen ihre eigenen Teams (`ownedTeams`) per UI umbenennen können. Eintrag erreichbar über das User-Menü oben rechts.

## Scope
- Neue Seite `/team` mit Liste der eigenen Teams (eine Karte pro Team, Name editierbar).
- Eintrag im UserMenu (Header-Dropdown) zwischen Settings und Logout.
- Sichtbar/erreichbar nur für `UserRole::ADMIN` und `UserRole::SUPER_ADMIN`.
- Edit beschränkt auf Teams, die der eingeloggte User besitzt (`owner_id = auth()->id()`).
- Nicht im Scope: Team erstellen, löschen, Mitglieder verwalten, Owner wechseln, current_team wechseln.

## Architektur

### Routes
`routes/web.php`, innerhalb der `auth,verified` Gruppe:

```php
Route::middleware('role:admin,super_admin')->group(function (): void {
    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::patch('/team/{team:uuid}', [TeamController::class, 'update'])->name('team.update');
});
```

### Controller
`app/Http/Controllers/Team/TeamController.php`

- Methoden `index()` und `update()` (kein `__invoke`, keine private helpers).
- `index()` rendert `team/Index` mit `Inertia::defer` für die Team-Liste.
- `update(UpdateTeamRequest, Team $team)` ruft `UpdateTeamAction::handle()` und macht `back()->with('success', ...)`.

### Action
`app/Actions/Team/UpdateTeamAction.php`

```php
public function handle(Team $team, UpdateTeamData $data): Team
{
    $team->update(['name' => $data->name]);

    return $team->fresh();
}
```

### DTO
`app/Data/Team/UpdateTeamData.php` — `final readonly class` mit `public string $name`. Statischer constructor `fromRequest(UpdateTeamRequest $request): self`.

### FormRequest
`app/Http/Requests/Team/UpdateTeamRequest.php`

- `authorize()`: `$this->user()->is($this->route('team')->owner)`.
- `rules()`: `['name' => ['required', 'string', 'max:255']]`.

### Authorization (doppelter Layer)
1. Route-Middleware `role:admin,super_admin` (verhindert dass `UserRole::USER` die Routes überhaupt erreicht).
2. FormRequest `authorize()` prüft Ownership (verhindert dass ein Admin ein fremdes Team updated).
3. `index()` filtert per `$user->ownedTeams()` — User sieht nur was er besitzt.

## Frontend

### Page
`resources/js/pages/team/Index.vue`

- `PageLayout` mit `title="Team"`.
- Grid: `grid-cols-1 lg:grid-cols-2 gap-4`.
- `<Deferred data="teams">` mit Skeleton-Placeholder (pulsing Card-Skeletons).
- Pro Team eine `Card` (title = aktueller Team-Name) mit Inertia `<Form>`:
  - `action="/team/{uuid}"`, `method="patch"`.
  - Input `name` (shadcn `Input` + `Label`).
  - Save-Button mit `Loader2 v-if="processing"` Spinner + stabilem Label "Save".
  - Field-Error unter Input (`text-destructive text-sm`).

### UserMenu
`resources/js/layout/UserMenu.vue`

- Neuer `DropdownMenuItem` zwischen Settings und Logout-Separator:
  ```vue
  <DropdownMenuItem v-if="canManageTeam" as-child>
      <Link href="/team"><Users class="size-4" /> Team</Link>
  </DropdownMenuItem>
  ```
- `canManageTeam = computed(() => ['admin', 'super_admin'].includes(user.value?.role ?? ''))`.
- Lucide-Icon `Users`.

### Types
`resources/js/types/team.ts`:

```ts
export interface OwnedTeam {
    id: number;
    uuid: string;
    name: string;
}
```

Re-Export über `resources/js/types/index.ts`.

User-Type muss `role: 'user' | 'admin' | 'super_admin'` enthalten (falls noch nicht vorhanden — prüfen, ggf. erweitern).

## Data Flow

`TeamController::index()`:

```php
return Inertia::render('team/Index', [
    'teams' => Inertia::defer(fn () => $request->user()
        ->ownedTeams()
        ->orderBy('name')
        ->get()
        ->map(fn (Team $t) => [
            'id' => $t->id,
            'uuid' => $t->uuid,
            'name' => $t->name,
        ])
        ->all()),
]);
```

Update: Form posts `PATCH /team/{uuid}` → Action → redirect back → Inertia reload bringt neue `teams` prop und Flash `success`.

## Validation / Errors
- `name`: `required|string|max:255`. Field-level Errors via `<Form>` `errors`-slot.
- Ownership-Verletzung → 403 → `errors/ErrorPage`.
- Role-Verletzung → 403 (Middleware) → `errors/ErrorPage`.

## Tests (Pest, Feature)
`tests/Feature/Team/TeamControllerTest.php`:

- `admin sees own teams on /team`
- `super admin sees own teams on /team`
- `regular user gets 403 on /team`
- `admin can rename own team`
- `admin gets 403 when updating foreign team`
- `update validates name (required, max:255)`

## Doku
`docs/help/2026_05_27-team-verwaltung-hinzugefuegt.md` — Pflicht laut CLAUDE.md, deutsche Sprache, benutzerorientiert.

## Pint
`vendor/bin/pint --dirty --format agent` nach PHP-Änderungen.

## Phasen (Pause nach jeder)

1. **Backend** — Route, Controller, DTO, Action, FormRequest + Pest-Tests grün.
2. **Frontend** — Page, UserMenu, Types, Manual-Check.
3. **Doku** — Help-Markdown.

## Out of Scope
- Team erstellen / löschen.
- Mitgliederverwaltung.
- Owner-Transfer.
- `current_team` wechseln.
- Filament-seitige Änderungen.