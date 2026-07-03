---
title: MySQL
description: Run MySQL in Peermitly — install one of the latest versions, start it with a click, open it in Adminer and connect your app in seconds.
---

# 🐬 MySQL

**MySQL** is the world's most popular open-source relational database. Peermitly installs and runs it for you like any other service — no terminal setup. Install a version, start it, and your app can connect right away.

## ⚙️ How it's built

Peermitly installs MySQL through **Homebrew** and manages it as a background service. You pick a version, Peermitly installs the matching formula, and from then on you **start**, **stop** and **restart** it straight from the dashboard.

You can keep **one version installed at a time**. To switch, remove the current version and install another.

## 🧩 Versions

Peermitly offers the latest MySQL releases:

- **9** — the current stable release, recommended for new projects.
- **8.4** — the LTS line.
- **8.0** — for projects pinned to the previous major.

Each version shows whether it's **installed**, **running**, and whether an **update** is available.

## 🔌 Port

MySQL runs on port **`3307`** by default — one above MariaDB, so you can run both side by side without a clash. You can change the port from the dashboard; the new value is applied on the next restart.

## ▶️ Start, stop & restart

Once a version is installed you control it from the MySQL card:

- **Start** — bring the service up.
- **Stop** — shut it down.
- **Restart** — apply config or port changes.

A small indicator shows the running version and the active port at a glance.

## 🔑 Root access

With one click Peermitly can **enable root access** — it sets up the `root` user with the password `root`, so your local apps and tools can connect without extra setup. This is meant for local development only.

## 🗂️ Adminer

When MySQL is running, open it in **Adminer** — a lightweight web database manager — straight from the card. Peermitly makes sure root access is set up first, so it just opens and connects.

## 🧾 Config & logs

- **Config** — edit the MySQL configuration file under the **Configs** view, then **restart** so changes take effect.
- **Log** — open the **Log** view to watch the live log output when something misbehaves.
- **Resource usage** — while running, Peermitly shows live CPU and memory usage.

## 🔗 Connect your app

For a Laravel app, add this to your `.env`:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

## 🚀 Get started

1. Open **MySQL** under **Databases** in the sidebar.
2. **Install** a version and **start** it.
3. Click **Adminer** to browse your data, or add the `.env` values to your app.

## 💡 Notes

- **Runs on port 3307** so it doesn't clash with MariaDB on 3306.
- **One version at a time.** Remove the installed version before installing another.
- **Local use.** The `root` / `root` credentials are for local development — don't reuse them anywhere public.
