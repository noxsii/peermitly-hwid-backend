<?php

declare(strict_types=1);

return [
    /*
     * Your API path. By default, all routes starting with this path will be added to the docs.
     * If you need to change this behavior, you can add your custom routes resolver using `Scramble::routes()`.
     */
    'api_path' => 'api',

    /*
     * Your API domain. By default, app domain is used. This is also a part of the default API routes
     * matcher, so when implementing your own, make sure you use this config if needed.
     */
    'api_domain' => null,

    /*
     * The path where your OpenAPI specification will be exported.
     */
    'export_path' => 'api.json',

    'info' => [
        /*
         * API version.
         */
        'version' => env('API_VERSION', '1.0.0'),

        /*
         * Description rendered on the home page of the API documentation (`/docs/api`).
         */
        'description' => <<<'MD'
# Peermitly API

Welcome to the **Peermitly** API — the licensing layer for your products.

Use this API to issue license keys, validate them from your application or
installer, bind keys to hardware, and manage products, key types, and
customers programmatically.

## Authentication

All endpoints require a Sanctum **Bearer token**.

```http
Authorization: Bearer <your-token>
```

Generate a token in the Peermitly dashboard under **Settings → API tokens**.
Each token belongs to a team and is scoped to specific abilities:

| Ability                       | Grants access to                                         |
|-------------------------------|----------------------------------------------------------|
| `license-keys:check`          | `POST /api/license-keys/check`                           |
| `license-keys:read`           | `GET  /api/license-keys`, `GET /api/license-keys/{uuid}` |
| `license-keys:manage`         | Create, revoke, restore, extend, delete license keys     |
| `license-key-types:manage`    | Full CRUD on key types                                   |

Tokens scope every request to the issuing team. There is no global access —
even admin tokens only see data of the team they were issued under.

## Conventions

- **IDs in URLs are UUIDs** (not numeric primary keys).
- **Timestamps** are ISO-8601 strings in UTC (`2026-05-21T08:30:00+00:00`).
- **Pagination** is page-based — request `?page=2`, response
  contains `data`, `meta`, and `links` keys. Page size is 25.
- **Errors** for invalid input return HTTP `422` with a
  `message` and an `errors` map keyed by field name.

## Rate limits

- `POST /api/license-keys/check` is limited to **60 requests per minute**
  per authenticated user + IP combination. Excess requests get HTTP `429`.
- Other endpoints inherit the default API throttle of **60 requests per
  minute** per token.

## The activation model

License keys are **activation-based**, not creation-based. A key in status
`pending` does not count down its validity until the first successful
check via `POST /api/license-keys/check`. Once activated, the key enters
status `active` and `expires_at` is set based on the key type's validity.

## HWID binding

A key created with `requires_hwid_check = true` must be checked with a
non-empty `hwid` field. The first check binds that HWID to the key
(stored as a hashed activation). Subsequent checks must present the
same HWID or the key returns status `hwid_mismatch`.

## Status reference

| Status      | Meaning                                                          |
|-------------|------------------------------------------------------------------|
| `pending`   | Created but not yet activated. Validity has not started.         |
| `active`    | Activated and within its validity period.                        |
| `expired`   | Validity period has ended (or `expires_at` is in the past).      |
| `revoked`   | Manually revoked by an admin. Will not pass checks.              |
| `blocked`   | Soft-blocked by the system (e.g. too many failed HWID attempts). |
MD,
    ],

    /*
     * Customize Stoplight Elements UI
     */
    'ui' => [
        /*
         * Define the title of the documentation's website. App name is used when this config is `null`.
         */
        'title' => null,

        /*
         * Define the theme of the documentation. Available options are `light`, `dark`, and `system`.
         */
        'theme' => 'light',

        /*
         * Hide the `Try It` feature. Enabled by default.
         */
        'hide_try_it' => false,

        /*
         * Hide the schemas in the Table of Contents. Enabled by default.
         */
        'hide_schemas' => false,

        /*
         * URL to an image that displays as a small square logo next to the title, above the table of contents.
         */
        'logo' => '',

        /*
         * Use to fetch the credential policy for the Try It feature. Options are: omit, include (default), and same-origin
         */
        'try_it_credentials_policy' => 'include',

        /*
         * There are three layouts for Elements:
         * - sidebar - (Elements default) Three-column design with a sidebar that can be resized.
         * - responsive - Like sidebar, except at small screen sizes it collapses the sidebar into a drawer that can be toggled open.
         * - stacked - Everything in a single column, making integrations with existing websites that have their own sidebar or other columns already.
         */
        'layout' => 'responsive',
    ],

    /*
     * The list of servers of the API. By default, when `null`, server URL will be created from
     * `scramble.api_path` and `scramble.api_domain` config variables. When providing an array, you
     * will need to specify the local server URL manually (if needed).
     *
     * Example of non-default config (final URLs are generated using Laravel `url` helper):
     *
     * ```php
     * 'servers' => [
     *     'Live' => 'api',
     *     'Prod' => 'https://scramble.dedoc.co/api',
     * ],
     * ```
     */
    'servers' => null,

    /**
     * Determines how Scramble stores the descriptions of enum cases.
     * Available options:
     * - 'description' – Case descriptions are stored as the enum schema's description using table formatting.
     * - 'extension' – Case descriptions are stored in the `x-enumDescriptions` enum schema extension.
     *
     *    @see https://redocly.com/docs-legacy/api-reference-docs/specification-extensions/x-enum-descriptions
     * - false - Case descriptions are ignored.
     */
    'enum_cases_description_strategy' => 'description',

    /**
     * Determines how Scramble stores the names of enum cases.
     * Available options:
     * - 'names' – Case names are stored in the `x-enumNames` enum schema extension.
     * - 'varnames' - Case names are stored in the `x-enum-varnames` enum schema extension.
     * - false - Case names are not stored.
     */
    'enum_cases_names_strategy' => false,

    /**
     * When Scramble encounters deep objects in query parameters, it flattens the parameters so the generated
     * OpenAPI document correctly describes the API. Flattening deep query parameters is relevant until
     * OpenAPI 3.2 is released and query string structure can be described properly.
     *
     * For example, this nested validation rule describes the object with `bar` property:
     * `['foo.bar' => ['required', 'int']]`.
     *
     * When `flatten_deep_query_parameters` is `true`, Scramble will document the parameter like so:
     * `{"name":"foo[bar]", "schema":{"type":"int"}, "required":true}`.
     *
     * When `flatten_deep_query_parameters` is `false`, Scramble will document the parameter like so:
     *  `{"name":"foo", "schema": {"type":"object", "properties":{"bar":{"type": "int"}}, "required": ["bar"]}, "required":true}`.
     */
    'flatten_deep_query_parameters' => true,

    'middleware' => [
        'web',
    ],

    'extensions' => [],
];
