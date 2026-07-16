---
title: Composer graph
description: The Peermitly Composer graph — turn any composer.lock into an interactive dependency map. See prod vs dev packages, inspect versions and licenses, and check how fresh your dependencies are.
---

# 🕸️ Composer graph

The **Composer graph** turns a project's `composer.lock` into an **interactive map of every package it depends on**. Instead of scrolling through thousands of lines of JSON, you get a visual graph where each package is a dot, lines connect packages to the things they require, and one click opens the details of any package.

It answers the questions a lock file makes hard to see at a glance: _What does this project actually pull in? Which packages are dev-only? What PHP version and extensions does it need? And how old are my dependencies?_

> **Free for everyone.** The Composer graph is available on every plan — no Pro license required.

![The Composer graph showing a project's dependency map next to the composer.lock file](/images/screenshots/composer_lock.png)

## 🚀 Open a project's graph

Point Peermitly at a project that has a `composer.lock` file. The app reads the lock file and draws the graph automatically — no install step, nothing to configure.

You get two things side by side:

- The **raw `composer.lock`** on the left, so you can always see the source.
- The **graph** on the right, built from that same lock file.

## 🟠 Prod vs dev at a glance

Every package is colored by where it belongs:

- **Prod** packages (`require`) — the dependencies your app ships with.
- **Dev** packages (`require-dev`) — tools you only need while developing, like PHPUnit or PHPStan.

Use the **Prod** and **Dev** toggles in the top corner to show or hide each group. Turning off dev packages is the fastest way to see what actually ends up in production.

## 🔍 Inspect any package

Click a dot to open its detail card. It shows:

- **Name and version** — exactly what is locked, e.g. `symfony/finder v8.1.1`.
- **Description** — what the package does.
- **License** — e.g. MIT.
- **Release date** — when that locked version was published.
- **Open homepage** — jumps to the package's project page.

The lines around the dot show what that package connects to, so you can follow a dependency down to the things it pulls in.

## 📊 The top bar

Above the graph, Peermitly summarizes the whole lock file:

- **Package counts** — how many **prod** and how many **dev** packages the project locks.
- **PHP constraint** — the PHP version the project requires (e.g. `^8.2`).
- **Required extensions** — every `ext-*` the project needs (`ext-mbstring`, `ext-openssl`, …), so you can see up front what the machine must have installed.
- **Date range** — the span from your oldest to your newest locked package.

## 🕰️ Freshness

Switch to the **Freshness** tab to see **how up to date your dependencies are**. Instead of the graph, packages are laid out by age — old, long-untouched dependencies stand out from recently released ones. It's a quick way to spot a package that hasn't been updated in years and might be worth a look.

## 💡 Why use it

- **Understand a new project fast.** Open the graph and you immediately see its size, its stack and its dev tooling — without reading a single line of the lock file.
- **Audit before you ship.** Toggle to prod-only and check exactly what your app depends on and under which licenses.
- **Check requirements.** The PHP constraint and `ext-*` list tell you what a machine needs before the project will even install.
- **Catch stale dependencies.** The Freshness view surfaces packages that have gone quiet.

## Requirements

The Composer graph needs a project with a valid `composer.lock` file. Run `composer install` (or `composer update`) in the project once so the lock file exists, then open it in Peermitly.
