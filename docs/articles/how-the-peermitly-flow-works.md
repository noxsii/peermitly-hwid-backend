# How the Peermitly flow works

**Title:** How the Peermitly flow works
**Slug:** `how-the-peermitly-flow-works`
**Excerpt:** From creating a product to a customer activating a license — the full end-to-end flow in Peermitly, in five steps.

---

Peermitly turns license management into a small, repeatable loop. Every license follows the same path: you define a product, generate keys, ship them to your customers, and your application calls our API to activate and check them.

This article walks through that loop end-to-end.

### 1. Set up a product

A **product** is whatever you sell — a desktop app, a plugin, a SaaS add-on. Each product is the bucket for all related license keys.

Open **Products → New product** and set:

- **Name** — what the customer sees.
- **Slug** — the identifier your API client will send (e.g. `pro-2026`).
- **Active** — disable to stop new activations without deleting history.

### 2. Define a license key type

A **license key type** describes the shape and behaviour of the keys you will issue:

- **Generator** — `random`, `pattern` (e.g. `PRMT-{XXXX}-{XXXX}-{XXXX}`), or `uuid`.
- **Validity** — days, weeks, months, years, hours, or lifetime.
- **Activation limit** — how many devices a single key may activate on.
- **HWID required** — if enabled, the activating device must send a hardware ID, which then binds the license to that machine.

Types are reusable. A typical setup has one type per pricing tier ("Trial", "Pro", "Lifetime").

### 3. Generate license keys

You have three ways to create keys:

- **Single key** from the License Keys page or via API (`POST /api/license-keys`).
- **Bulk create** from the License Keys page — generate up to **1,000 keys** in one batch. You receive an in-app notification when it finishes.
- **Bulk extend** existing keys forward by a fixed duration (only keys expiring on or after a chosen threshold are touched).

Newly generated keys start in the `pending` state. They do not consume their validity period until the first activation.

### 4. Hand the keys to your customers

Export selected keys from the License Keys page as a CSV with your preferred delimiter. From here it is your distribution channel — license email, in-app purchase flow, partner portal.

### 5. Your application calls the check API

This is the part your product does on every launch:

```
POST https://peermitly.de/api/license-keys/check
Authorization: Bearer <your API token>
Content-Type: application/json

{
  "key": "PRMT-9F2A-4E11-XR07",
  "product": "pro-2026",
  "hwid": "optional-hardware-id"
}
```

The endpoint responds with one of:

- `active` — license is valid. If it was the first call, the key transitions from `pending` to `active`, and `expires_at` is computed from the configured validity.
- `expired` — past `expires_at`.
- `revoked` — manually revoked.
- `invalid` — key or product not found.
- `product_mismatch` — key exists but does not belong to the product you sent.
- `hwid_required` — the type requires an HWID and none was provided.
- A `403` if the activation limit is exceeded for a new device.

The response also tells you whether this was the **first activation** so you can show a welcome screen, and returns the licensed product slug so your client can react accordingly.

### Transparency

- **API request logs** — every call to `/api/*` is logged (IP, user agent, status, duration) and visible under **Logs**. Retained for 30 days.
- **Activity logs** — every change to a license key or user (created, updated, revoked, restored, extended) is tracked with the responsible user.
- **Status page** — operational status is at [peermitly.betteruptime.com](https://peermitly.betteruptime.com/). The underlying `GET /api/health` endpoint returns 200 (or 503 on dependency failure) for your own monitoring.

### A quick mental model

Think of Peermitly as three rings around your product:

1. **You define the rules** (products, types).
2. **You generate the tickets** (keys, bulk or single).
3. **Your customers' devices punch the tickets** (`/api/license-keys/check`).

Everything else — expiry, activation counts, HWID locking, revocation, audit logs — is bookkeeping that we handle around those three steps.