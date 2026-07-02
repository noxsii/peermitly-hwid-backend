---
title: Debug
description: The Peermitly Debug tool — dump values from any PHP or front-end site with dd(), dump(), peer() or console.log and see them live in a searchable window, no packages to install.
---

# 🐛 Debug

The **Debug** tool gives you **live insights from your sites**. Call `dd()`, `dump()` or `peer()` anywhere in your PHP code — or `peer()` and `console.log` in your Vue, React and Astro sites — and the value shows up instantly in the Debug window, fully expandable, searchable, and tagged with where it came from. No Composer package, no npm package, no config, no browser extension: it works in **every** Peermitly site out of the box.

Think of it as a built-in dump viewer. Instead of dumping into the middle of your HTML response (and breaking the page), your values are sent to a separate window where you can read them in peace.

![The Debug window showing a dump](/images/screenshots/dump_1.png)

## 🔌 Turn it on and off

Debug can be **switched on or off** with the toggle in the Debug window. The small **dot** next to the title shows the state:

- **On** — Peermitly captures dumps from your sites and shows them live.
- **Off** — your `dd()` / `dump()` / `peer()` calls are ignored and nothing is captured.

Turn it **off** when you're done debugging so stray dump calls don't do anything, and **on** when you want to inspect values again. The setting applies to all your sites at once.

> Debug works on Peermitly PHP sites automatically — there is nothing to install in your project. The helper functions are made available to every site while Debug is enabled.

## 🧰 Three ways to dump

You use the tool straight from your PHP code with one of three functions. Pass it anything — a variable, an array, a Collection, an object, several values at once.

- **`dd($value)` — "dump and die".** Sends the value to the Debug window and then **stops the script immediately**. Use it when you want to inspect something and don't care about the rest of the request.
- **`dump($value)` — dump and continue.** Sends the value and lets the script **keep running**. Use it to watch several points in a request without stopping it. It **returns the value**, so you can wrap it inline: `$total = dump($cart->total());`.
- **`peer($value)` — the Peermitly dump.** Works exactly like `dump()` (sends and continues, returns the value) and is guaranteed to be the Peermitly function even if a framework defines its own `dump()`. Use `peer()` when you always want the value in this window.

| Function | Sends to Debug | Stops the script | Returns the value |
|----------|:--------------:|:----------------:|:-----------------:|
| `dd()`   | ✅ | ✅ (dies) | — |
| `dump()` | ✅ | ❌ | ✅ |
| `peer()` | ✅ | ❌ | ✅ |

You can pass **several values at once** — `dd($user, $order, $total)` — and each one appears in the entry.

## 🟨 JavaScript (Vue / React / Astro sites)

Debug isn't just for PHP — it also captures from the browser on your front-end sites. In **any** component or file, `peer()` is available **globally**, so there is nothing to import:

```js
// your own call (recommended, orange badge):
peer(user);
peer("checkout state", cart, total);

// or just keep logging as usual — it's captured automatically:
console.log("hello", { a: 1 });
console.warn("careful");
console.error(new Error("boom"));
```

Then open the page in your browser at **`http://your-site.peer`** — this only works over the **`.peer` domain**, not `localhost:5173`, because the capture script is injected by Peermitly's proxy. Every log then lands **live** in the Debug window with a yellow **JS** badge, the matching `peer` / `console.log` badge, and the `file.js:line` it came from.

> **TypeScript complaining about `peer(...)`?** Add a one-liner to the site's `src/vite-env.d.ts`:
>
> ```ts
> declare function peer(...vars: unknown[]): unknown;
> ```

## 🔎 What each dump shows

Every entry in the Debug window is tagged so you always know where it came from:

- **Time** — when the dump was sent (e.g. `13:13:27`).
- **Language** — a badge showing where it came from: PHP, or a yellow **JS** badge for browser dumps.
- **Function** — which call produced it: `dd()`, `dump()`, `peer()` on the PHP side, or `peer()` / `console.log` / `console.warn` / `console.error` in the browser.
- **Site** — the site that sent it, e.g. `meine-laravel-app.peer`.
- **Request** — the HTTP method and path, e.g. `GET /`.
- **Source** — the exact file and line the call sits on, e.g. `routes/web.php:8` or `App.vue:12` — so you can jump straight back to it.

The value itself is shown as an **expandable tree**. Arrays, Collections, objects and enums are typed and can be folded open and closed. Long strings and very large/deeply nested structures are trimmed so the window stays fast.

## 🔍 Search

Use the **search box** to filter and find things inside your dumps. As you type, matching text is **highlighted** and the counter shows how many entries match (e.g. `1 / 1 dump`). Search looks through the dumped content, not just the titles — handy when you have many dumps and are hunting for one key or value.

![Searching within dumps](/images/screenshots/dump_2.png)

## 🧹 Clear and manage

- **Clear** — the **Clear** button empties the list so you can start fresh for the next request.
- **Keep on top** — pin the Debug window so it stays visible while you work in your editor and browser.
- **History limit** — Peermitly keeps the most recent dumps (the oldest fall off automatically), so a busy app never fills up memory.

## ▶️ How to use it

1. Open the **Debug** window and make sure it's switched **on**.
2. In your PHP code, add a dump where you want to look:

```php
Route::get('/', function () {
    $items = collect([1, 2, 3, 4, 5]);

    peer($items);       // send it to Debug, keep going
    dump($items->sum()); // watch a second value too

    return view('welcome');
});
```

3. Load the page in your browser (`https://your-name.peer`).
4. Switch to the **Debug** window — your values appear at the top, newest first.
5. Use **`dd()`** instead when you just want to inspect and stop:

```php
dd($request->all());
```

## 💡 Notes

- **For local development.** Debug is a development helper. Remove your `dd()` / `dump()` / `peer()` calls before shipping — and if any are left, turning Debug **off** stops them from doing anything.
- **Works everywhere.** Laravel, Symfony or plain PHP — the functions are available in every Peermitly PHP site while Debug is on. On Vue, React and Astro sites, `peer()` and your `console` logs are captured too — as long as you open the site over its `.peer` domain.
- **`dd()` really stops.** Like the framework `dd()`, it ends the request. The browser will show a blank/partial page — that's expected; check the Debug window for the value.
- **Nothing showing up?** Make sure Debug is switched **on**, that the code path actually runs, and reload the page.
