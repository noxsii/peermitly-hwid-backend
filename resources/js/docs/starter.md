---
title: Starter guide
description: Get your first site running in Peermitly — install and start nginx and DNS, then wait for the services indicator in the header to turn green.
---

# 🚀 Starter guide

This guide takes you from a fresh install to your first working `.peer` site. The key step: get the **services** running so the indicator in the header turns **green**.

Already installed Peermitly and the required tools? If not, do the [Setup](/guide/setup) guide first.

## 🟢 The services indicator

At the top of the app, in the header, there is a **server icon** with a small status dot. That dot tells you at a glance whether your environment is ready:

- ⚪️ **Grey** — no services installed yet.
- 🟠 **Amber** — something is installed but not running, or DNS isn't set up yet.
- 🟢 **Green** — everything is installed, running and resolving. You're ready to build.

![The services indicator in the header](/images/screenshots/services_header.png)

Your goal for this guide is simple: **make that dot green.**

## 🧩 Step 1 — Open Services

Click the **server icon** in the header to open the services menu. You'll see the services Peermitly manages — **nginx** and **DNS** — each with its own status.

If they show **Not installed** or **Stopped**, that's what we'll fix next.

## ⚙️ Step 2 — Install and start nginx

nginx is the web server that serves your sites.

1. Open the **nginx** service (click it in the services menu, or go to the **nginx** screen).
2. Click **Install** and wait for it to finish.
3. Click **Start**.

The nginx dot turns **green** once it is installed and running.

## 🌐 Step 3 — Install and start DNS

DNS is what makes your `.peer` domains resolve locally, so `my-app.peer` points to your machine.

1. Open the **DNS** screen (from the services menu).
2. Click **Install**, then **Start**.
3. Wait until DNS shows **Working** for the `.peer` domain.

## ✅ Step 4 — Check the header

Go back to the header. With **nginx running** and **DNS working**, the services dot turns **green**.

> If it stays amber, open the services menu and check which item is still **Stopped** or **Not set up** — start that one. The dot only turns green when _everything_ is ready.

## 🎉 You're ready

With the indicator green, Peermitly can serve sites. Add a project and it becomes a live `.peer` URL with automatic HTTPS — no extra setup.

## Troubleshooting

- **Dot won't turn green.** Open the services menu and start whatever still shows **Stopped**. DNS must show **Working** too.
- **A site doesn't load.** Make sure nginx is running, then reload. If needed, restart it from the nginx screen.
- **Wrong PHP or Node version.** See [PHP versions & settings](/guide/php) and [Node versions](/guide/node) to pick the right one per project.
