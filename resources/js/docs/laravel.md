---
title: Laravel
description: Create a new Laravel site in Peermitly — choose a starter kit, testing framework and Git, all served on a secure .peer domain.
---

# 🎯 Laravel

Peermitly can scaffold a brand-new **Laravel** project for you and serve it on a `.peer` domain with HTTPS — no terminal steps. It uses the official **Laravel installer** under the hood, and keeps that installer up to date for you automatically, so every new project starts from the latest version.

## ⚙️ How it's built

When you create a Laravel site, Peermitly runs the official `laravel new` installer. The first time it's needed, Peermitly installs it; after that it **updates the installer automatically** before each new project — you always get the current Laravel release without managing anything yourself.

## 🧩 Options

When you create a Laravel site you choose:

### Starter kit

Laravel's official starter kits set up authentication and a frontend for you:

- **No Starter Kit** — a clean Laravel install with no frontend scaffolding. Best when you'll add your own stack.
- **React** — starter kit with a React frontend (Inertia).
- **Vue** — starter kit with a Vue frontend (Inertia).
- **Livewire** — starter kit with a Livewire frontend (PHP-driven, no separate JS framework).

### Testing framework

Pick which testing tool the project is set up with:

- **Pest** — the modern, expressive testing framework (default).
- **PHPUnit** — the classic PHP testing framework.

### Git

- **On** — initialise a Git repository in the new project right away.
- **Off** — skip Git; you can `git init` later yourself.

## 🌐 Site options

Alongside the framework choices, every site has:

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
2. Choose **Laravel**.
3. Pick your **starter kit**, **testing framework** and whether to enable **Git**.
4. Set the **name**, **PHP version** and **HTTPS**.
5. Create it. Peermitly runs the installer and wires up the `.peer` domain.

When it finishes, open the site at `https://your-name.peer`.

## 💡 Notes

- The Laravel installer is **managed for you** — no need to run `composer global require laravel/installer` yourself.
- Need a specific PHP version? Install and activate it first — see [PHP versions & settings](/guide/php).
- Working with an existing Laravel project instead of a new one? Point Peermitly at the folder and it serves it on a `.peer` domain the same way.
