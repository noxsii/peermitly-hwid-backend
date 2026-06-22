# Permitly Landing Page — Design

**Date:** 2026-05-21
**Status:** Approved (brainstorming round)
**Style direction:** Clean SaaS light (option B)
**Scope:** Focused (hero + features + CTA + footer)

## Goal

Replace the bare `/` route (currently `view('app')` with no content) with a small, polished, English-language marketing landing page that introduces Permitly and routes visitors to the sign-in screen. No registration anywhere — invitations only.

## Non-Goals

- No dark-mode toggle on the landing page (light only).
- No registration, no sign-up CTA, no "create account" copy.
- No pricing, no testimonials, no FAQ, no blog, no animations beyond hover/transition.
- No analytics integration, no SEO meta beyond a page `<title>`.
- No new dependencies — uses what is already in `package.json` / `composer.json`.

## Routing & Backend

- Route `GET /` becomes a controller endpoint, not an inline closure.
  - File: `app/Http/Controllers/LandingController.php`
  - Method: `show(Request $request): Response|RedirectResponse`
  - Behavior:
    - Authenticated → `redirect()->route('dashboard')`
    - Guest → `Inertia::render('Landing/Index')`
  - No `__invoke` (per project rule: one controller per concern, always named methods).
- Route name: `home`.
- No props are passed today; if props are added later they must be wrapped in `Inertia::defer()` per project convention.

## Page & Component Layout

```
resources/js/pages/Landing/Index.vue           ← page, defineOptions({ layout: "" })
resources/js/components/landing/
  ├─ LandingNav.vue          ← top nav: logo wordmark + Sign in button (right)
  ├─ LandingHero.vue         ← eyebrow, h1, sub, CTA pair, decorative key card
  ├─ LandingFeatures.vue     ← three feature tiles in a responsive grid
  ├─ LandingCta.vue          ← closing call-to-action band
  └─ LandingFooter.vue       ← thin footer, © + minimal links
```

Each component is a leaf SFC. No prop drilling required — feature data lives inside `LandingFeatures.vue` as a typed local constant.

## Types

- All shared types go in `resources/js/types/` per project rule. The only new type is internal to `LandingFeatures.vue`:

  ```ts
  // resources/js/types/landing.ts
  export interface LandingFeature {
      icon: Component;     // lucide icon component
      title: string;
      description: string;
  }
  ```

  Even though it is currently only consumed by one component, it lives in `types/` to keep the project rule consistent.

## Visual Design

- **Theme:** light only on the landing page. The component sets `<div class="bg-background text-foreground">` on the root and does not opt into dark variants.
- **Container:** `mx-auto max-w-6xl px-6` shared across sections; section padding `py-20 md:py-28`.
- **Hero background:** `bg-gradient-to-b from-background to-muted/40` for subtle depth, with a faint radial accent behind the headline (single CSS `radial-gradient`, no SVG asset).
- **Typography:**
  - H1: `text-5xl md:text-6xl font-semibold tracking-tight`
  - Eyebrow: `text-xs font-medium tracking-[0.18em] uppercase text-muted-foreground`
  - Sub-heading: `text-lg md:text-xl text-muted-foreground max-w-2xl`
- **Buttons:** shadcn-vue `<Button>` only.
  - Primary CTA: `variant="default"` with `ArrowRight` icon.
  - Secondary CTA: `variant="outline"`.
- **Feature tiles:** `rounded-2xl border bg-card p-6 shadow-sm` with a small icon chip on top (`size-10 rounded-lg bg-primary/10 text-primary`).
- **CTA band:** dark `bg-foreground text-background rounded-3xl p-10 md:p-14` to break visual rhythm and signal the close.
- **Footer:** single line, `border-t py-8 text-sm text-muted-foreground`.

## Copy (English — final)

- **Nav wordmark:** `Permitly`
- **Eyebrow:** `LICENSE MANAGEMENT`
- **Headline:** `Ship software. Control access.`
- **Sub:** `License keys, HWID binding, and an activation API for your own products — in one calm dashboard.`
- **Primary CTA label:** `Sign in`
- **Secondary CTA label:** `How it works` (anchors to `#features`)
- **Feature 1 — `KeyRound` icon:**
  - Title: `Built for activation`
  - Body: `Keys stay pending until the first API call. Customers never lose days they didn't use.`
- **Feature 2 — `ShieldCheck` icon:**
  - Title: `Hardware-bound`
  - Body: `Optional HWID lock per key. One license, one machine, zero abuse.`
- **Feature 3 — `Zap` icon:**
  - Title: `One simple API`
  - Body: `POST /api/license-keys/check. Token-secured. Drop it into any product.`
- **CTA band headline:** `Lock down your product today.`
- **CTA band sub:** `Sign in and start issuing license keys in minutes.`
- **CTA band button:** `Sign in →`
- **Footer:** `© 2026 Permitly`  ·  `Documentation`  ·  `Status` (links are placeholders to `#` for now; the design does not block on real URLs).

## Login Button Behavior

- Renders Inertia `<Link :href="route('login')">` (Ziggy-exposed named route, path `/login`) inside shadcn `<Button>`.
- Always visible: top-right of nav AND inside hero AND in CTA band.
- No "Sign up" link anywhere on the page or in the footer.

## Accessibility

- Single `<h1>` (hero).
- Buttons use real `<a>` tags via Inertia `<Link>` for sign-in (proper navigation semantics).
- All decorative SVGs receive `aria-hidden="true"`.
- Color contrast at WCAG AA against the chosen theme tokens.

## Tests

Feature test only — Pest, in `tests/Feature/Http/LandingPageTest.php`:

1. `guest sees the landing page` — `GET /` returns 200, asserts Inertia component `Landing/Index`.
2. `authenticated user is redirected to the dashboard` — login as factory user, `GET /` redirects to `/dashboard`.
3. `the page exposes a sign-in link and no registration link` — the response body contains a link to the `login` route (`/login`) and does NOT contain the string `Sign up` / `Register`.

No unit tests required (no business logic added).

## Documentation

Per `CLAUDE.md` (`.ai/docs` rule), add a German help document for the change:

`docs/help/2026_05_21-landing-page.md`

Contents follow the existing template (Titel, Datum, Bereich, Kurzbeschreibung, Was ist neu?, Warum wurde das geändert?, Wie funktioniert es?, Betroffene Bereiche, Wichtige Hinweise, Beispiel, Technische Notizen).

## Files Touched

**New:**
- `app/Http/Controllers/LandingController.php`
- `resources/js/pages/Landing/Index.vue`
- `resources/js/components/landing/LandingNav.vue`
- `resources/js/components/landing/LandingHero.vue`
- `resources/js/components/landing/LandingFeatures.vue`
- `resources/js/components/landing/LandingCta.vue`
- `resources/js/components/landing/LandingFooter.vue`
- `resources/js/types/landing.ts`
- `tests/Feature/Http/LandingPageTest.php`
- `docs/help/2026_05_21-landing-page.md`

**Edited:**
- `routes/web.php` — swap the `/` closure for the controller route.

## Verification Plan

1. `php artisan test --compact --filter=LandingPage` — all green.
2. `vendor/bin/pint --dirty --format agent` — clean.
3. `bun run build` — succeeds.
4. Manual: open `/` as guest → see landing. Log in → `/` redirects to `/dashboard`.