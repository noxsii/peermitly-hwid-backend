---
title: Meilisearch
description: Run Meilisearch in Peermitly — a fast, typo-tolerant search engine. Install one of the three latest versions, start it with a click and connect your app in seconds.
---

# 🔍 Meilisearch

**Meilisearch** is a fast, open-source search engine with typo tolerance, instant results and a friendly API. Peermitly can install and run it for you like any other service — no terminal setup, no config files to hunt down. Install a version, start it, and your app can talk to it right away.

## ⚙️ How it's built

Peermitly installs Meilisearch through **Homebrew** and manages it as a background service. You pick a version, Peermitly installs the matching formula, and from then on you **start**, **stop** and **restart** it straight from the dashboard.

You can keep **one version installed at a time** — pick the one your project needs. To switch, remove the current version and install another.

## 🧩 Versions

Peermitly offers the **three latest** Meilisearch versions, just like Redis and the databases:

- **Latest** — the current stable release, recommended for new projects.
- **Previous two** — earlier releases for projects that are pinned to a specific version.

Each version shows whether it's **installed**, **running**, and whether an **update** is available. Installing a newer patch is one click when Peermitly detects one.

## 🔌 Port

Meilisearch runs on port **`7700`** by default. You can change the port from the dashboard — the new value is applied the next time the service restarts.

## ▶️ Start, stop & restart

Once a version is installed you control it from the Meilisearch card:

- **Start** — bring the service up.
- **Stop** — shut it down.
- **Restart** — apply config or port changes.

A small indicator shows the running version and the active port at a glance.

## 🖥️ Open the dashboard

Meilisearch ships with its own **web dashboard**. When the service is running, use the **Open Dashboard** link to open it in your browser at `http://localhost:7700` — a built-in UI where you can browse your indexes and try out searches without writing any code.

## 🧾 Config

Peermitly points at Meilisearch's configuration under the **Configs** view, where you can edit the settings file and save it. **Restart** Meilisearch afterwards so the changes take effect.

> If no config file exists yet, the Configs view stays empty — Meilisearch simply runs on its defaults (port `7700`, local data directory).

## 📜 Logs

Open the **Log** view from the Meilisearch card to watch the live log output — helpful when a search request misbehaves or the service won't start. The log streams as new lines are written.

## 📊 Resource usage

While Meilisearch is running, Peermitly shows its **CPU and memory usage** live, so you can keep an eye on how much the search engine is consuming.

## 🔗 Connect your app

Point your application at the local Meilisearch instance. For a Laravel app using **Scout**, add this to your `.env`:

```ini
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=
```

Leave `MEILISEARCH_KEY` empty for local development unless you've set a master key.

## ✅ Before you start

- **Homebrew** must be available — Peermitly installs Meilisearch through it.
- The services indicator **green** so the rest of your environment is up. If not, do the [Starter guide](/guide/starter) first.

## 🚀 Get started

1. Open **Meilisearch** under **Search** in the sidebar.
2. **Install** one of the three offered versions.
3. **Start** it — the port and running version appear in the card.
4. Click **Open Dashboard** to explore your indexes in the browser.
5. Add the `.env` values above to your app and start indexing.

## 💡 Notes

- **One version at a time.** Remove the installed version before installing another.
- **Restart after changes.** Port and config changes apply on the next restart.
- **Local use.** The default setup is meant for local development — no master key required.
