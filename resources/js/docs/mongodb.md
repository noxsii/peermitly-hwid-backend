---
title: MongoDB
description: Run MongoDB in Peermitly — install one of the latest community versions, start it with a click and connect with mongosh or your driver.
---

# 🍃 MongoDB

**MongoDB** is a popular open-source document database. Peermitly installs and runs the **Community** edition for you like any other service — no terminal setup. Install a version, start it, and connect right away.

> **Free for everyone.** MongoDB is available on every plan — no Pro license required.

## ⚙️ How it's built

Peermitly installs MongoDB Community through **Homebrew** (from the official `mongodb/brew` tap) and manages it as a background service. You pick a version, Peermitly installs the matching formula, and from then on you **start**, **stop** and **restart** it straight from the dashboard.

You can keep **one version installed at a time**. To switch, remove the current version and install another.

## 🧩 Versions

Peermitly offers the latest MongoDB Community releases:

- **8.2** and **8.0** — current stable lines, recommended for new projects.
- **7.0** — for projects pinned to the previous major.

Each version shows whether it's **installed**, **running**, and whether an **update** is available.

## 🔌 Port

MongoDB runs on port **`27017`** by default. You can change the port from the dashboard — the new value is applied on the next restart.

## ▶️ Start, stop & restart

Once a version is installed you control it from the MongoDB card:

- **Start** — bring the service up.
- **Stop** — shut it down.
- **Restart** — apply config or port changes.

A small indicator shows the running version and the active port at a glance.

## 🧾 Config & logs

- **Config** — edit the MongoDB configuration file under the **Configs** view, then **restart** so changes take effect.
- **Log** — open the **Log** view to watch the live log output when something misbehaves.
- **Resource usage** — while running, Peermitly shows live CPU and memory usage.

## 🔗 Connect

MongoDB runs without authentication locally, so you can connect with **`mongosh`** or any driver using the connection string:

```text
mongodb://127.0.0.1:27017
```

## 🚀 Get started

1. Open **MongoDB** under **Databases** in the sidebar.
2. **Install** a version and **start** it.
3. Connect with `mongosh` or your app's driver using the connection string above.

## 💡 Notes

- **Community edition.** Peermitly installs MongoDB Community from the `mongodb/brew` tap.
- **No Adminer.** Unlike the SQL databases, MongoDB has no Adminer view — use `mongosh` or a GUI like Compass.
- **One version at a time.** Remove the installed version before installing another.
- **Local use.** The default setup has no authentication — meant for local development only.
