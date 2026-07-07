---
title: Blank PHP
description: Serve any plain PHP folder in Peermitly on a secure .peer domain — pick your own entry file, no framework required.
---

# 🐘 Blank PHP

Not every project is a framework. Sometimes you just have a folder of `.php` files — a small script, a legacy app, an experiment. Peermitly serves that folder on a `.peer` domain with PHP-FPM and optional HTTPS, without you touching nginx or a config file.

## ⚙️ How it works

A **Blank PHP** site points Peermitly at a folder you already have. It does **not** scaffold anything or create files — your folder stays exactly as it is. Peermitly writes an nginx vhost, makes sure PHP-FPM is running, and serves the site.

You choose which **entry file** requests are routed to:

- Leave it at the default and Peermitly serves `public/index.php`.
- Or pick any `.php` file inside the folder (e.g. `index.php` in the root, or `web/app.php`).

The folder that holds your entry file becomes the document root, and that file handles the requests.

## 🧩 Options

When you create a Blank PHP site you choose:

### Folder

The existing folder you want to serve. Nothing inside it is changed.

### Entry file

The `.php` file that handles incoming requests.

- **Default** — `public/index.php` (the common layout for modern PHP apps).
- **Custom** — any `.php` file in the folder, including one in the root like `index.php`.

The file must exist and end in `.php`. Files outside the folder or hidden paths are not allowed.

## 🌐 Site options

Alongside the folder and entry file, every site has:

- **Name** — used for the `.peer` domain (e.g. `shop` → `shop.peer`).
- **PHP version** — which installed PHP version the site runs on.
- **HTTPS** — serve the site over a trusted local certificate (recommended).

## ✅ Before you start

Creating a site needs your environment ready — the services indicator in the header should be **green**:

- **nginx** running (serves the site).
- **DNS** working (so `.peer` resolves).

If it isn't green yet, follow the [Starter guide](/guide/starter) first.

## 🚀 Create the site

1. Open **Sites** and start a new site.
2. Choose **PHP**.
3. Select the **folder** you want to serve.
4. Keep the default entry file or pick your own `.php` file.
5. Set the **name**, **PHP version** and **HTTPS**.
6. Create it. Peermitly wires up the `.peer` domain and starts PHP-FPM.

When it finishes, open the site at `https://your-name.peer`.

## 💡 Notes

- Blank PHP always serves an **existing folder** — it never generates a project for you. Use [Laravel](/guide/laravel) or [Symfony](/guide/symfony) if you want a fresh app scaffolded.
- No `public/` folder? Point the entry file at the `.php` file in your root instead.
- Need a specific PHP version? Install and activate it first — see [PHP versions & settings](/guide/php).