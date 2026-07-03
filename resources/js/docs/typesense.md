---
title: Typesense
description: Run Typesense in Peermitly — a fast, typo-tolerant search engine. Install one of the three latest versions, start it with a click and connect your app in seconds.
---

# 🔎 Typesense

**Typesense** is a fast, open-source search engine with typo tolerance and instant results — a lightweight alternative to Elasticsearch. Peermitly can install and run it for you like any other service — no terminal setup, no config files to hunt down. Install a version, start it, and your app can talk to it right away.

## ⚙️ How it's built

Peermitly installs Typesense through **Homebrew** and manages it as a background service. You pick a version, Peermitly installs the matching formula, and from then on you **start**, **stop** and **restart** it straight from the dashboard.

You can keep **one version installed at a time** — pick the one your project needs. To switch, remove the current version and install another.

## 🧩 Versions

Peermitly offers the **three latest** Typesense versions, just like Meilisearch, Redis and the databases:

- **Latest** — the current stable release, recommended for new projects.
- **Previous two** — earlier releases for projects that are pinned to a specific version.

Each version shows whether it's **installed**, **running**, and whether an **update** is available. Installing a newer release is one click when Peermitly detects one.

## 🔌 Port

Typesense runs on port **`8108`** by default. You can change the port from the dashboard — the new value is applied the next time the service restarts.

## ▶️ Start, stop & restart

Once a version is installed you control it from the Typesense card:

- **Start** — bring the service up.
- **Stop** — shut it down.
- **Restart** — apply config or port changes.

A small indicator shows the running version and the active port at a glance.

## 🔑 API key

Typesense requires an **admin API key**. Out of the box Peermitly uses the default key **`xyz`**, which is fine for local development. For anything beyond your machine, change it in the config file (`typesense.ini`) and restart the service.

## 🧾 Config

Peermitly points at Typesense's configuration file **`typesense.ini`** under the **Configs** view, where you can edit settings like the API port and the admin API key. **Restart** Typesense afterwards so the changes take effect.

## 📜 Logs

Open the **Log** view from the Typesense card to watch the live log output — helpful when a search request misbehaves or the service won't start. The log streams as new lines are written.

## 📊 Resource usage

While Typesense is running, Peermitly shows its **CPU and memory usage** live, so you can keep an eye on how much the search engine is consuming.

## 🔗 Connect your app

Point your application at the local Typesense instance. For a Laravel app using **Scout**, add this to your `.env`:

```ini
SCOUT_DRIVER=typesense
TYPESENSE_HOST=127.0.0.1
TYPESENSE_PORT=8108
TYPESENSE_PROTOCOL=http
TYPESENSE_API_KEY=xyz
```

The default admin API key is `xyz` — change it in `typesense.ini` for anything beyond local use.

## ✅ Before you start

- **Homebrew** must be available — Peermitly installs Typesense through it.
- The services indicator **green** so the rest of your environment is up. If not, do the [Starter guide](/guide/starter) first.

## 🚀 Get started

1. Open **Typesense** under **Search** in the sidebar.
2. **Install** one of the three offered versions.
3. **Start** it — the port and running version appear in the card.
4. Add the `.env` values above to your app and start indexing.

## 💡 Notes

- **One version at a time.** Remove the installed version before installing another.
- **Restart after changes.** Port, API key and config changes apply on the next restart.
- **Local use.** The default `xyz` API key is meant for local development — change it before exposing Typesense anywhere else.
