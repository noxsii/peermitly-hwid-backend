---
title: Symfony
description: Create a new Symfony site in Peermitly — choose between a full web app or a minimal skeleton, served on a secure .peer domain.
---

# 🎼 Symfony

Peermitly can scaffold a brand-new **Symfony** project for you and serve it on a `.peer` domain with HTTPS — no terminal steps. It uses the official **Symfony CLI** under the hood, and keeps that CLI up to date for you automatically, so every new project starts from the current version.

## ⚙️ How it's built

When you create a Symfony site, Peermitly runs `symfony new`. The first time it's needed, Peermitly installs the Symfony CLI; after that it **updates the CLI automatically** before each new project — you always get the current version without managing anything yourself.

## 🧩 Options

When you create a Symfony site you choose:

### Starter kit

This decides how much comes pre-configured:

- **Webapp** — a full web application setup. Symfony adds the common web packages (Twig templates, forms, database/Doctrine, and more) so you can build a traditional web app right away. Best for websites and full apps.
- **Skeleton** — a minimal Symfony install with just the core. You add only the packages you need. Best for APIs, microservices or a lean custom setup.

### Git

- **On** — initialise a Git repository in the new project right away.
- **Off** — skip Git; you can `git init` later yourself.

## 🌐 Site options

Alongside the framework choices, every site has:

- **Name** — used for the project folder and its `.peer` domain (e.g. `shop` → `shop.peer`).
- **PHP version** — which installed PHP version the site runs on.
- **HTTPS** — serve the site over a trusted local certificate (recommended).

## ✅ Before you start

Creating a site needs your environment ready — the services indicator in the header should be **green**:

- **nginx** running (serves the site).
- **DNS** working (so `.peer` resolves).

If it isn't green yet, follow the [Starter guide](/guide/starter) first.

## 🚀 Create the site

1. Open **Sites** and start a new site.
2. Choose **Symfony**.
3. Pick your **starter kit** (Webapp or Skeleton) and whether to enable **Git**.
4. Set the **name**, **PHP version** and **HTTPS**.
5. Create it. Peermitly runs the Symfony CLI and wires up the `.peer` domain.

When it finishes, open the site at `https://your-name.peer`.

## 💡 Notes

- The Symfony CLI is **managed for you** — no need to install or update it yourself.
- Not sure which starter kit? Choose **Webapp** for a website or full app, **Skeleton** for an API or minimal service.
- Need a specific PHP version? Install and activate it first — see [PHP versions & settings](/guide/php).
- Working with an existing Symfony project instead of a new one? Point Peermitly at the folder and it serves it on a `.peer` domain the same way.
