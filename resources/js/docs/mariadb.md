---
title: MariaDB
description: Run MariaDB in Peermitly — install one of the latest versions, start it with a click, open it in Adminer and connect your app in seconds.
---

# 🦭 MariaDB

**MariaDB** is a fast, open-source relational database and a drop-in replacement for MySQL. Peermitly installs and runs it for you like any other service — no terminal setup. Install a version, start it, and your app can connect right away.

![MariaDB in the Peermitly dashboard](/images/screenshots/maria_db.png)

## ⚙️ How it's built

Peermitly installs MariaDB through **Homebrew** and manages it as a background service. You pick a version, Peermitly installs the matching formula, and from then on you **start**, **stop** and **restart** it straight from the dashboard.

You can keep **one version installed at a time**. To switch, remove the current version and install another.

## 🧩 Versions

Peermitly offers the latest MariaDB releases:

- **11.8** and **11.4** — current stable lines, recommended for new projects.
- **10.6** and **10.5** — long-term releases for projects pinned to an older version.

Each version shows whether it's **installed**, **running**, and whether an **update** is available.

## 🔌 Port

MariaDB runs on port **`3306`** by default. The card always shows the **active port** next to the running version, and you can change it from the dashboard — the new value is applied on the next restart. Use the port shown on the card in your app's connection settings.

## ▶️ Start, stop & restart

Once a version is installed you control it from the MariaDB card:

- **Start** — bring the service up.
- **Stop** — shut it down.
- **Restart** — apply config or port changes.

A small indicator shows the running version and the active port at a glance.

## 🔑 Root access

With one click Peermitly can **enable root access** — it sets up the `root` user with the password `root`, so your local apps and tools can connect without extra setup. This is meant for local development only.

## 🗂️ Adminer

When MariaDB is running, open it in **Adminer** — a lightweight web database manager — straight from the card. Peermitly makes sure root access is set up first, so it just opens and connects.

## 🧾 Config & logs

- **Config** — edit the MariaDB configuration file under the **Configs** view, then **restart** so changes take effect.
- **Log** — open the **Log** view to watch the live log output when something misbehaves.
- **Resource usage** — while running, Peermitly shows live CPU and memory usage.

## 🔗 Connect your app

For a Laravel app, add this to your `.env`:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

## 🚀 Get started

1. Open **MariaDB** under **Databases** in the sidebar.
2. **Install** a version and **start** it.
3. Click **Adminer** to browse your data, or add the `.env` values to your app.

## 💡 Notes

- **One version at a time.** Remove the installed version before installing another.
- **Restart after changes.** Port and config changes apply on the next restart.
- **Local use.** The `root` / `root` credentials are for local development — don't reuse them anywhere public.
