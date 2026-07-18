---
title: Grav CMS
description: Create a new Grav CMS site in Peermitly or serve an existing one — a flat-file PHP CMS with no database, running on a secure .peer domain.
---

# 🪨 Grav CMS

Peermitly can scaffold a brand-new **Grav** project for you and serve it on a `.peer` domain with HTTPS — no terminal steps. Grav is a **flat-file** PHP CMS: it stores your content as files, so there's **no database to set up**.

## ⚙️ How it's built

When you create a Grav site, Peermitly runs `composer create-project getgrav/grav` under the hood. The first time it's needed, Peermitly installs **Composer** for you, so you don't have to prepare anything yourself — you just get a ready-to-use Grav install.

## 🌐 Site options

Grav keeps setup simple — there are no starter kits or Git options to pick. Every site has:

- **Name** — used for the project folder and its `.peer` domain (e.g. `blog` → `blog.peer`).
- **PHP version** — which installed PHP version the site runs on.
- **HTTPS** — serve the site over a trusted local certificate (recommended).

## ✅ Before you start

Creating a site needs your environment ready — the services indicator in the header should be **green**:

- **nginx** running (serves the site).
- **DNS** working (so `.peer` resolves).

If it isn't green yet, follow the [Starter guide](/guide/starter) first.

## 🚀 Create the site

1. Open **Sites** and start a new site.
2. Choose **Grav CMS**.
3. Set the **name**, **PHP version** and **HTTPS**.
4. Create it. Peermitly installs Grav with Composer and wires up the `.peer` domain.

When it finishes, open the site at `https://your-name.peer` and continue in Grav's own admin.

## 📂 Serve an existing Grav project

Already have a Grav site? Point Peermitly at the folder instead of creating a new one. Choose the **Grav project root** — the folder that contains `index.php` and the `system` folder. Peermitly serves that root directly through PHP, so Grav's routing works out of the box.

## 💡 Notes

- **No database** — Grav stores everything as files, so there's nothing extra to install or connect.
- **Composer is managed for you** — no need to install it yourself.
- Need a specific PHP version? Install and activate it first — see [PHP versions & settings](/guide/php).
- When adding an existing project, make sure you pick the **root** folder (with `index.php` and `system/`), not a subfolder — otherwise Peermitly can't serve it.
