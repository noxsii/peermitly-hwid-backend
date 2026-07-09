---
title: Profiler
description: The Peermitly Profiler — one-click SPX profiling for every PHP site. Record requests, see wall time, memory and call counts, and open full flame-graph reports right in your browser.
---

# 📈 Profiler

The **Profiler** shows you **where your PHP code spends its time**. It is powered by [SPX](https://github.com/NoiseByNorthwest/php-spx), a low-overhead PHP profiling extension — installed, configured and wrapped in a clean Peermitly interface for you. Flip a switch, browse your site, and every request shows up with its **wall time, peak memory and number of function calls**. Click a request and you get the full analysis: flame graph, timeline and per-function breakdown.

No Composer package, no manual extension builds, no php.ini editing — Peermitly handles all of it.

> **Pro feature.** The Profiler is part of Peermitly **Pro**.

![The Peermitly Profiler showing a recorded request](/images/screenshots/php-spx.png)

## 📦 Install it

1. Open **Settings → Profiler** in the app.
2. Click **Install**.

Peermitly downloads the latest SPX release and **builds it for your active PHP version** — you can watch the build log live in the app. The whole thing usually takes under a minute.

Two things need to be in place:

- **Xcode Command Line Tools** — required to compile the extension. If they are missing, run `xcode-select --install` in a terminal and try again.
- **An active PHP version** managed by Peermitly.

When a new SPX release is available, the Install button becomes an **Update** button — one click rebuilds the profiler on the latest version.

## 🔌 Turn it on and off

After installing, tick **"Enable profiler for all PHP sites"** in the same settings tab. Peermitly then:

- loads the SPX extension into your active PHP version,
- generates a private access key for you,
- adds a **`/peermitly-profiler`** route to every PHP site,
- restarts PHP-FPM so everything applies immediately.

Untick the box and all of it is removed again — php.ini stays clean, no leftovers.

> Enabling the profiler makes it _available_. It only records requests once you also turn on **Profiling** inside the profiler UI (see below) — so your sites run at full speed until you actually want to profile.

## ▶️ Profile a request

1. Open **`https://your-site.peer/peermitly-profiler`** in your browser — or click one of the ready-made **Profiler links** in the app's settings tab.
2. Flip the **Profiling** switch in the sidebar. It records requests **from this browser only**, so a colleague's tab or your test runner won't fill up your list.
3. Browse your site like you normally would.
4. Switch back to the profiler — every request appears in the **Requests** list, newest first, with its method, URL, **wall time**, **peak memory** and **recorded calls**. The list refreshes automatically.
5. **Click a request** to open the full report: flame graph, timeline and per-function metrics, powered by SPX's analysis engine.

Use the **Filter by URL** field to find a specific request when the list gets long.

## ⚙️ Profiler settings

The **Settings** view inside the profiler UI gives you the SPX recording options:

- **Auto start** — automatically record every request while Profiling is on (default). Turn it off to trigger recording manually.
- **Profile internal functions** — include PHP's built-in functions in the report, not just your own code.
- **Sampling** — trade precision for lower overhead on very hot code paths.
- **Max depth** — limit how deep the call tree is recorded.
- **Metrics** — choose what is measured: wall time, CPU time, memory, and more.

The defaults are right for everyday use — you only need this view when you dig deeper.

## 💡 Notes

- **Local only.** The profiler is bound to your machine (localhost) and protected by a private key that Peermitly manages for you. Nothing is exposed to the network, and profile data is stored locally on your Mac.
- **Works on every PHP site.** Laravel, Symfony, WordPress or a blank PHP folder — every PHP site gets the `/peermitly-profiler` route. Node-based sites (Vue, React, Nuxt, Next.js, Astro) are not profiled.
- **Switched PHP versions?** The extension is built per PHP version. After switching, the app shows a short notice — click **Install** once and it is rebuilt for the new version.
- **Overhead.** Recording adds a little overhead per request. Leave the Profiling switch off when you are not measuring — with it off, your sites behave exactly as before.
- **Uninstall** removes the extension, all configuration and all recorded profiles.
