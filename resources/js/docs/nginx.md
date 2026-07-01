---
title: nginx
description: What nginx does in Peermitly and how to manage it — install, start, stop and restart the web server that serves your local sites over HTTPS.
---

# 🌐 nginx

**nginx** is the web server that sits in front of all your local sites. In Peermitly it is the piece that turns a project folder into a real, browsable site on a secure `.peer` domain. You manage it from the **Services** area — install it, start it, and Peermitly handles the rest.

## 🧩 What Peermitly uses nginx for

- **Serves your sites.** Every site you add is served by nginx, reachable at its own `.peer` address (for example `my-app.peer`).
- **Handles HTTPS.** nginx terminates the trusted local TLS certificate, so your sites open over **https** with no browser warnings.
- **Routes each domain to the right project.** One nginx instance serves all your sites at once and sends each `.peer` domain to the correct folder.

Without nginx running, your sites have nothing serving them — which is why the [Starter guide](/guide/starter) has you start it first.

## ✅ Install and start

On the **nginx** screen (open it from the services menu in the header):

1. Click **Install** and wait for it to finish.
2. Click **Start**.

Once installed and running, nginx shows **Running** with a green dot — and it counts toward the green services indicator in the header.

## 🎛️ Managing the service

The nginx screen gives you full control over the service:

- **Start** — bring nginx up so your sites are served.
- **Stop** — take nginx down (your sites stop responding until you start it again).
- **Restart** — apply configuration changes or recover a site that stopped responding.
- **Update** — upgrade nginx to a newer release when one is available.
- **Uninstall** — remove nginx entirely if you no longer need it.

> After changing any nginx configuration, use **Restart** so the new settings take effect.

## 📝 Configuration

nginx is configured through its `nginx.conf` file. Peermitly lets you view and edit it directly from the **Configs** view on the nginx screen — no need to hunt for the file on disk. Edit, save, then **Restart** nginx to apply.

Most setups never need to touch this — Peermitly ships a sensible configuration out of the box. It's there for advanced tweaks when you need them.

## 📄 Logs

Two logs help you see what nginx is doing:

- **Access log** — every request that hits your sites.
- **Error log** — problems nginx ran into (the first place to look when a site won't load).

Open either from the nginx screen to watch requests live or debug an issue.

## ⚠️ "Another nginx is already running"

If Peermitly warns that **another nginx is already running**, it means a second nginx — one that Peermitly did **not** start (for example a system install, or one from Docker or a manual Homebrew setup) — is using the web ports.

Two nginx servers cannot both own ports 80 and 443, so this blocks Peermitly's nginx from serving your sites correctly.

**Fix it** by stopping the other nginx, then start Peermitly's:

```bash
# find a foreign nginx started by Homebrew
brew services list

# stop it if present
brew services stop nginx

# or stop a manually started one
sudo nginx -s stop
```

Once no foreign nginx is running, start nginx from within Peermitly.

## Requirements

nginx is installed and managed for you — you just need Peermitly set up first. See the [Setup](/guide/setup) guide, then the [Starter guide](/guide/starter) to get everything green.
