# Landing Page Redesign — Peermitly

## Date

2026-05-31

## Goal

Expand the thin public landing page (`/`, `landing/Index.vue`) into a fuller, premium
"Refined Minimal" marketing page that explains the product to developers, while staying
inside the existing shadcn-vue / Tailwind v4 token system. No layout dependencies added
(one shadcn component generated: Accordion).

## Design Direction

Refined Minimal: elevate the current calm light style — generous whitespace, hairline
borders, subtle radial gradient glows, mono accents for keys/code, rounded-2xl cards.
Light + Dark both supported via tokens only; **no hardcoded colors**. Frontend copy in
**English**.

## Page Structure (`resources/js/pages/landing/Index.vue`)

Order top → bottom:

1. `LandingNav` — keep. Add anchor links in the header: `How it works` (#how), `API` (#api), `FAQ` (#faq). Keep the `Sign in` button.
2. `LandingHero` — keep, light polish only.
3. `LandingTrustBand` — **NEW**.
4. `LandingHowItWorks` — **NEW**.
5. `LandingFeatures` — keep, minor polish.
6. `LandingUseCases` — **NEW**.
7. `LandingApi` — **NEW**.
8. `LandingFaq` — **NEW**.
9. `LandingCta` — keep.
10. `LandingFooter` — **EXPANDED**.

## New Components

All under `resources/js/components/landing/`. Data-driven from typed arrays declared in
`resources/js/types/landing.ts` (never inside SFCs).

### LandingTrustBand
Thin strip directly under the hero, hairline top/bottom border, muted background.
4 inline items, each a tiny lucide icon + short label:
- `HWID-locked` (ShieldCheck)
- `Fully hosted` (Cloud)
- `Activation API` (Webhook / Plug)
- `99.9% uptime` (Activity) — links to the external status page (`https://peermitly.betteruptime.com/`).
Wraps gracefully on mobile (flex-wrap, centered).

### LandingHowItWorks  (`id="how"`)
3 numbered steps, row on desktop (`md:grid-cols-3`), stacked on mobile.
Each step: large ghost number (`01` / `02` / `03`), lucide icon, title, one-line description.
Hairline connector between steps on desktop only (decorative, `aria-hidden`).
Steps:
1. **Create a key** — Generate a license key in the dashboard. It stays pending until first use.
2. **Drop in the API** — Call `POST /api/license-keys/check` from your product. Token-secured.
3. **Customer activates** — First check binds the key (optional HWID lock) and starts the clock.

### LandingUseCases
Section heading + eyebrow, then 4 cards (`md:grid-cols-2 lg:grid-cols-4`), same
rounded-2xl / hairline-border / shadow-sm card language as `LandingFeatures`.
- **Desktop apps** (MonitorSmartphone) — Lock native apps to a machine with HWID.
- **Plugins & extensions** (Puzzle) — Gate premium add-ons behind a key.
- **Indie SaaS** (Rocket) — Issue keys per customer without building billing-grade infra.
- **Internal tools** (Building2) — Control who runs your in-house software.

### LandingApi  (`id="api"`)
Two columns on desktop (`md:grid-cols-2`), stacked on mobile.
- Left: short copy — heading "One endpoint. Token-secured.", paragraph, small note that the
  key stays pending until the first call.
- Right: dark code card (intentionally dark in both themes, matching the existing CTA block
  pattern — uses `bg-foreground text-background` style, not a hardcoded hex). Header row shows
  `POST /api/license-keys/check` plus a **copy button** (lucide `Copy` → `Check` on success)
  top-right. Body shows a realistic request JSON and response JSON (key, status `active`,
  hwid, expires_at). Monospace.

### LandingFaq  (`id="faq"`)
shadcn-vue `Accordion` (generate via `bunx shadcn-vue@latest add accordion`).
~5 items:
1. What is HWID lock? — Optional binding of a key to one machine fingerprint.
2. Why do keys stay pending? — The runtime clock starts on first activation, not on creation, so customers never lose unused days.
3. Do I need to run any servers? — No, Peermitly is a fully hosted service; nothing to install.
4. Is the API rate-limited? — Yes, the check endpoint is token-secured and throttled.
5. How do I revoke a key? — Revoke from the dashboard; the next check fails.

### LandingFooter (expanded)
Replace the single-row footer with: brand blurb (left) + link columns (right).
Columns:
- **Product**: How it works (#how), API (#api), FAQ (#faq)
- **Resources**: Documentation (external), Status (external)
- **Legal**: Privacy (`route('privacy')`)

Keep the `© {year} Peermitly` line. **Do not** link Changelog/Help — those routes are
auth-gated and would redirect anonymous visitors to login.

## Polish Mechanics

- **Scroll-reveal**: new composable `resources/js/composables/useScrollReveal.ts`.
  IntersectionObserver-based; adds a reveal/visible class when a section enters the viewport,
  unobserves after first reveal. **Guards `prefers-reduced-motion: reduce`** — when set, content
  is shown immediately with no transition. Returns a `ref` to attach to a section root.
  Applied to each new section (and optionally the kept ones).
- **Copy**: composable `resources/js/composables/useCopyToClipboard.ts` — `copy(text)` writes to
  clipboard, sets a `copied` ref true, resets after ~2s. Used by `LandingApi`.
- **Types**: extend `resources/js/types/landing.ts` with `LandingStep`, `LandingUseCase`,
  `LandingFaqItem` (and `LandingTrustItem` if useful). Reuse existing `LandingFeature` shape.

## Constraints (project rules)

- Frontend strings English; PHP/docs German.
- Types in `resources/js/types/`, never in SFCs.
- Use shadcn-vue components (Accordion) — do not roll own.
- No hardcoded colors; tokens + the existing radial-gradient pattern only.
- Single root element per Vue component.
- No new runtime deps beyond the generated shadcn Accordion.

## SEO / Meta Tags

Currently the landing only sets `<Head title>`; `app.blade.php` has just charset/viewport/csrf.
Add full meta via Inertia `<Head>` on `landing/Index.vue`. Absolute URLs must be
environment-correct, so the controller passes them as props (do **not** hardcode a domain).

`LandingController::show()` adds props:
- `canonical` = `url('/')` (absolute home URL for the current environment)
- `ogImage` = `url('/og-image.png')`

`<Head>` on the landing renders:
- `<title>` — `Peermitly — License keys, HWID binding & activation API`
- `meta name="description"` — one-sentence product summary (~150 chars)
- `link rel="canonical"` → `canonical`
- `meta name="robots"` → `index, follow`
- Open Graph: `og:type=website`, `og:site_name=Peermitly`, `og:title`, `og:description`,
  `og:url` (= canonical), `og:image` (= ogImage), `og:locale=en_US`
- Twitter: `twitter:card=summary_large_image`, `twitter:title`, `twitter:description`,
  `twitter:image` (= ogImage)
- `meta name="theme-color"` (token-neutral brand color)

Title/description strings live as `const`s in the SFC `<script setup>` (single source, reused
across OG/Twitter), props typed in `resources/js/types/landing.ts` if passed
(`LandingSeoProps`). All strings English.

**og:image asset**: a real 1200×630 PNG must exist at `public/og-image.png`. None exists yet —
the plan includes a placeholder task to add a branded image there; the meta tags reference it
regardless so they are correct once the asset is dropped in.

## Testing

- Pest browser/smoke test: visit `/` and assert no JS console errors, and that the new section
  anchors/text render (`How it works`, `API`, `FAQ`, a use-case title). Add to existing landing
  test if one exists, otherwise a new `tests/Browser` smoke test.

## Documentation

Per CLAUDE.md, add one German help doc: `docs/help/2026_05_31-landingpage-erweitert.md`
describing the new sections from a user/support perspective.

## Out of Scope (YAGNI)

Pricing, testimonials, blog, i18n, new icon/font dependencies. The controller change is limited
to passing SEO URL props; no DB/model/route changes.