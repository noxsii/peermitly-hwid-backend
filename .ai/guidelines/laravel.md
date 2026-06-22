# Laravel DDD & Architecture Guidelines

## Domain Structure (DDD)
- **Strict Layering:**
    - **Controllers:** Http entry point only. Map request to DTO, call Action, return Resource.
    - **Actions:** The ONLY place for business logic. Single responsibility (e.g., `CreateOrderAction`).
    - **Models:** Thin Active Records. No business logic.
    - **DTOs:** Strict data transport between layers.

## Enums
- **Enums:** Use native PHP 8.1+ Backed Enums.
- **Migrations:** NEVER use `$table->enum()`.
- **Casting:** Explicitly cast string columns to Enums in the Model's 
- **Validation:** Use `in:` rule with Enum cases for request validation.
- **Tests:** Use `->assertEnum()` for request validation.
- **Cases** only in UpperCase

## Multi-Tenancy (Team-based)
- **Tenant:** The `Team` model is the central tenant.
- **Migrations:** New domain tables MUST have: `$table->foreignId('team_id')();`
- **Creation:** Actions creating data MUST explicitly set the `team_id`.

## The "Thin Model" Rule
- **No Logic:** Models must not contain calculations or side effects.
- **No Scopes:** Do not write local scopes in Models.
- **Custom Builders:** Move all Eloquent query logic to dedicated `Builder` classes.
    - *Usage:* `$order->newQuery()->whereActive()->get()`

## Static Analysis & Types (PHPStan)
- **Strict Types:** `declare(strict_types=1);` is mandatory in all files.
- **Generics:** MUST use DocBlock Generics for Collections/Arrays.
    - *Example:* `/** @return Collection<int, Order> */`
- **Level 9:** Code must be compatible with PHPStan Level 9 (no `mixed`, explicit types).

## Enums & Database
- **PHP Enums:** Use native PHP 8.1+ Backed Enums.
- **Migrations:** NEVER use `$table->enum()`. Use `$table->string()` and cast it in the Model.
- **Casting:** Explicitly cast string columns to Enums in the Model's `$casts`.

## Modern PHP 8.4 Rules
- **Property Hooks:** Use Property Hooks to define computed properties in DTOs and Value Objects.
    - *Do:* `public string $fullName { get => $this->first . ' ' . $this->last; }`
    - *Don't:* Write `getFullName()` methods.
- **Asymmetric Visibility:** Use `public private(set)` for properties that should be readable publicly but mutable only internally (if not using `readonly` classes).
- **Array Functions:** Use native PHP 8.4 functions like `array_find()` and `array_any()` instead of loops or heavy Collection wrappers for simple array operations.
- **Syntax:** Use `match` over `switch`. Use `?->` for null safety.

## Action Pattern
- **Method:** Use `handle` or `execute`.
- **Composition:** Actions can inject other Actions.
- **No Repositories:** Use Eloquent Builders inside Actions.
- **Actions** are located in app/Actions, and are the only place for business logic. They should be single-purpose (e.g., `CreateOrderAction`).

## Service Pattern
- **Method:** Use `call`.
- **Composition:** Services can inject other Actions they in **Services/Actions**.
- **No Repositories:** Use Eloquent Builders inside Services.

## Filament
- **Filament:** Use Filament for admin panel.
- **Filament Resources:** Use Filament Resources for admin panel.
- **Filament Actions:** Use Filament Actions for admin panel.
- **Filament Components:** Use Filament Components for admin panel.
- **Filament Hooks:** Use Filament Hooks for admin panel.
- **Language:** Use German Language for Filament naming conventions.
- **Testing:** Use Filament Testing for admin panel.
