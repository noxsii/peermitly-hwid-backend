# LicenseGate — License Key System V1 Design

## Context

Permitly needs a complete license-key system: admin UI to issue and manage
keys, public API to validate them, and activation-based duration (the
runtime starts at the first successful API check, not at creation).

## Decisions (frozen 2026-05-20)

- **API auth**: Laravel Sanctum personal access tokens with per-token
  abilities. Users manage tokens themselves in `/settings`.
- **HWID per key**: each key has a `requires_hwid_check` switch. When
  enabled, every API check must include `hwid`. Combined with
  `max_activations` it serves as device lock.
- **Machine binding**: fully in V1 via `license_key_activations` table.
- **Seed defaults**: 4 default `license_key_types` per team (Standard,
  Trial, Enterprise, UUID), idempotent.
- **Routes**: dedicated `App\Providers\RouteServiceProvider` loads
  `routes/auth.php`, `routes/settings.php`, `routes/license-keys.php`;
  `web.php` and `api.php` stay in `bootstrap/app.php`.
- **DTOs**: all generator config + check payloads use typed
  `final readonly class` in `app/Data/LicenseKeys/`, never `array`
  shapes between layers.
- **Inertia data**: every controller prop wrapped in `Inertia::defer`,
  frontend renders `<Deferred>` placeholder/skeleton.
- **Dialogs over pages**: `New`, `Edit`, `Bulk` flows are dialogs on the
  index page, not standalone `/create` and `/edit` pages.
- **No invokable controllers**: every action method is named
  (`revoke()`, `restore()`, `extend()`).
- **No native confirm/alert**: shadcn `Dialog` via `ConfirmDialog`
  wrapper for every confirmation.

## Tenancy

`team_id` foreign key on every domain table. Web routes scope by
`auth()->user()->current_team_id`. API routes scope identically via the
authenticated user behind the Sanctum token.

## Status flow

```
[create]
   │
   ▼
 pending  ──first successful check──►  active  ──now() > expires_at──►  expired
   │                                     │
   │                                     ├──admin revoke──► revoked  ──restore──►  active|expired|pending
   │                                     └──admin block──► blocked
   │
   └──admin revoke (before activation)──► revoked
```

## Key flow on /api/license-keys/check

1. Normalise raw key.
2. Lookup `normalized_key` scoped to `team_id` from the authenticated
   token.
3. Not found → log `not_found`, respond generic `invalid`.
4. Wrong product slug → `product_mismatch`.
5. Status `revoked` / `blocked` → respective response.
6. `requires_hwid_check` true and no `hwid` in request → `hwid_required`
   (HTTP 422). Key stays `pending`.
7. Status `pending` → `ActivateLicenseKeyAction` atomically sets
   `activated_at = now()`, computes `expires_at`, status = `active`,
   registers HWID activation when applicable.
8. `expires_at` in the past → set `status = expired`, respond.
9. HWID match check (active key + requires_hwid_check + hwid sent):
   - existing → update `last_seen_at`.
   - new + within `max_activations` → register.
   - new + limit reached → `activation_limit_reached`.
10. Touch `last_checked_at`, increment `check_count`, log
    `license_key_checks` row, return `LicenseKeyCheckResult`.

## Architecture summary

```
app/
├── Actions/LicenseKeys/        Activate, BulkCreate, Check, Create,
│                               Extend, Generate, Normalize, Restore, Revoke
├── Data/LicenseKeys/           Configuration (abstract + Uuid/Random/Pattern),
│                               GenerationContext, GenerationResult,
│                               CheckRequest, CheckResult
├── Enums/                      LicenseKeyStatus, LicenseKeyGeneratorType,
│                               LicenseValidityUnit, LicenseCheckStatus
├── Http/Controllers/
│   ├── LicenseKeys/            LicenseKeyController, LicenseKeyTypeController,
│   │                           LicenseKeyTypePreviewController,
│   │                           LicenseKeyRevokeController,
│   │                           LicenseKeyRestoreController,
│   │                           LicenseKeyExtendController,
│   │                           LicenseKeyExportController
│   ├── Api/                    LicenseKeyCheckController (check),
│   │                           LicenseKeyController (admin CRUD),
│   │                           LicenseKeyTypeController (admin CRUD)
│   └── Settings/               ApiTokenController (store JSON / destroy)
├── Models/                     Product, Customer, LicenseKey,
│                               LicenseKeyType, LicenseKeyCheck,
│                               LicenseKeyActivation
│   └── Builders/               LicenseKeyBuilder, LicenseKeyTypeBuilder
├── Services/LicenseKeys/
│   └── Generators/             Uuid, RandomCharacter, Pattern,
│                               LicenseKeyGeneratorFactory
└── Support/                    TokenAbility (constants)

resources/js/
├── components/
│   ├── dialogs/                ConfirmDialog, CreateLicenseKeyDialog,
│   │                           BulkCreateLicenseKeyDialog,
│   │                           EditLicenseKeyTypeDialog,
│   │                           CreateApiTokenDialog, ShowApiTokenDialog
│   ├── license-keys/           LicenseKeyForm (legacy), LicenseKeyTypeForm,
│   │                           LicenseKeyTable, LicenseKeyTypeTable,
│   │                           LicenseKeyStatusBadge,
│   │                           LicenseKeyTypeRowActions
│   │   └── columns/            licenseKeyColumns.ts, licenseKeyTypeColumns.ts
│   ├── settings/               PasswordCard, ApiTokensCard
│   ├── table/                  DataTable, DataTableColumnHeader,
│   │                           DataTablePagination (tanstack-vue-table)
│   ├── ui/                     button, input, label, switch, tooltip,
│   │                           dropdown-menu, select, checkbox, dialog,
│   │                           alert-dialog, table, badge, skeleton,
│   │                           textarea
│   ├── Card.vue
│   └── InputError.vue
└── pages/license-keys/         Index, types/Index, Show, Edit
```

## API endpoints

```
POST   /api/license-keys/check                           ability:license-keys:check + throttle 60/min
GET    /api/license-keys                                 ability:license-keys:read
GET    /api/license-keys/{uuid}                          ability:license-keys:read
POST   /api/license-keys                                 ability:license-keys:manage
DELETE /api/license-keys/{uuid}                          ability:license-keys:manage
POST   /api/license-keys/{uuid}/revoke|restore|extend    ability:license-keys:manage
GET    /api/license-key-types[/{uuid}]                   ability:license-key-types:manage
POST   /api/license-key-types                            ability:license-key-types:manage
PATCH  /api/license-key-types/{uuid}                     ability:license-key-types:manage
DELETE /api/license-key-types/{uuid}                     ability:license-key-types:manage
```

## Data model (essentials)

`license_keys`:

```
id (bigint) | uuid (unique) | team_id | license_key_type_id |
product_id | customer_id? | created_by? | key (unique) | normalized_key |
status (enum string) | validity_amount? | validity_unit |
max_activations? | requires_hwid_check | activated_at? | expires_at? |
last_checked_at? | check_count | revoked_at? | revoked_reason? |
metadata (json?) | timestamps
unique([team_id, normalized_key])
index([team_id, status]), index(expires_at)
```

`license_key_activations`:

```
id | uuid | team_id | license_key_id | machine_id |
hostname? | ip_address? | user_agent? | activated_at | last_seen_at? |
revoked_at? | metadata? | timestamps
unique([license_key_id, machine_id])
```

## Verification (matches what currently ships)

- `vendor/bin/pint --format agent` → clean
- `php artisan migrate` (no `migrate:fresh`)
- `php artisan db:seed --class=LicenseKeyTypeSeeder` → 4 default types
  per team (idempotent)
- `php artisan test --compact` → 114 tests, 305 assertions, all green
- `bun run build` → clean Vite build
- Manual browser smoke: login → sidebar → license-keys → create type
  with preview → create key dialog → bulk dialog → revoke/restore →
  settings → create api token → use token via `curl POST
  /api/license-keys/check` → first check returns `first_activation: true`
  with computed expires_at, second check returns `first_activation: false`
  with identical expires_at.

## Not in V1

CSV import, webhook events, customer portal, automatic license emails,
detailed statistics dashboard, custom email templates.

## Risks & special cases

1. **Race on first activation**: `lockForUpdate()` inside DB transaction
   in `ActivateLicenseKeyAction`.
2. **Key uniqueness**: unique constraint on `(team_id, normalized_key)`
   plus retry loop in `GenerateLicenseKeyAction` (max 10 attempts → throw
   `LicenseKeyGenerationException`).
3. **Plain token leak**: returned exactly once via `ApiTokenController::store`
   JSON response, never stored or logged.
4. **Route name backwards compatibility**: existing names (`login`,
   `dashboard`, `settings.edit`, `settings.password.update`, `logout`)
   unchanged.
