---
title: PHP versions & settings
description: Install, switch and configure PHP versions in Peermitly — run multiple versions side by side, manage PHP-FPM and tune per-version settings.
---

# 🐘 PHP

Peermitly manages PHP for you through **Homebrew**. You can install several PHP versions, switch the active one with a single click, run PHP-FPM, and tune the most important `php.ini` settings per version — all from the **PHP** screen, no terminal required.

## 🧩 What Peermitly does with PHP

- **Installs and removes PHP versions** via Homebrew (e.g. 8.1, 8.2, 8.3, 8.4).
- **Switches the active version** so the `php` command and your sites use the one you pick.
- **Runs multiple versions side by side** — different projects can use different PHP versions.
- **Manages PHP-FPM** — shows whether the FPM process is running and lets you restart it.
- **Keeps versions up to date** with one click.
- **Edits the key `php.ini` settings** (memory, upload size, execution time) per version.

Everything runs **natively** on your machine — no Docker, no virtual machine.

## 📥 Install a PHP version

On the **PHP** screen you see every supported PHP version. To add one:

1. Find the version you want in the list.
2. Click **Install**.
3. Peermitly installs it through Homebrew and shows the progress live.

Once finished, the version is ready to activate. You can install as many versions as you need.

> The first install of a version can take a little longer because Homebrew compiles and links it. Later actions are much faster.

## ✅ Activate the version you want

Only one PHP version is the **active** one at a time. The active version is what your terminal's `php` command and your sites use by default.

1. Click **Activate** (or **Default**) on the version you want.
2. Peermitly links it into Homebrew's bin directory at `/opt/homebrew/bin/php`.
3. The version is now marked **Active**.

Verify it in a terminal:

```bash
php -v
```

> If your terminal still shows a different PHP after activating, your shell `PATH` is pointing somewhere else. See [Shell is not using the Homebrew PHP](/guide/homebrew-php-path) to fix it.

## 🔀 Run multiple versions side by side

You don't have to choose just one. Install several versions and switch the active one whenever a project needs a different one — for example, keep a legacy project on PHP 8.1 while building a new one on PHP 8.4.

Switching is instant and non-destructive: your installed versions and their settings stay exactly as you left them.

## ⚙️ PHP-FPM

PHP-FPM is the process that serves your PHP sites. For each version, Peermitly shows whether FPM is **running** or **stopped**.

If a site stops responding or you changed a setting that needs a fresh start, click **Restart FPM**. Peermitly restarts the FPM process for that version cleanly.

## 🎚️ Tune PHP settings

Each version has its own settings, editable from the **Settings** dialog. Peermitly reads the current values from `php.ini` and writes your changes back safely.

| Setting | What it controls | Typical value |
|---|---|---|
| `memory_limit` | Maximum memory a single script may use | `256M` – `512M` |
| `upload_max_filesize` | Largest single file that can be uploaded | `64M` |
| `post_max_size` | Largest total size of a POST request (must be ≥ upload size) | `64M` – `128M` |
| `max_execution_time` | Seconds a script may run before timing out | `30` – `60` |

To change them:

1. Open **Settings** on the version you want to configure.
2. Adjust the values.
3. Save. Peermitly writes them to that version's `php.ini`.
4. Click **Restart FPM** so the new values take effect for your sites.

> Settings are **per version**. Changing `memory_limit` on PHP 8.3 does not affect PHP 8.4. Switch the dialog to the other version to configure it too.

## 🔄 Update a version

When a new patch release of a PHP version is available, an **Update** action appears. Click it to update that version through Homebrew. Your settings are preserved.

## 🗑️ Remove a version

No longer need a version? Click **Remove**. Peermitly uninstalls it via Homebrew. If you remove the currently active version, activate another one afterwards so your `php` command keeps working.

## 🧪 Verify everything works

After installing or switching versions, a quick check in your terminal confirms the setup:

```bash
which php
# -> /opt/homebrew/bin/php

php -v
# shows the version you activated

php -m
# lists the loaded extensions
```

If `which php` does not point to `/opt/homebrew/bin/php`, your shell `PATH` needs adjusting — follow [Shell is not using the Homebrew PHP](/guide/homebrew-php-path).

## 💡 Tips

- **Match the project's version.** Activate the PHP version your project actually targets before installing dependencies, so Composer resolves the right packages.
- **Bump upload limits for big uploads.** If file uploads fail, raise both `upload_max_filesize` **and** `post_max_size`, then restart FPM.
- **Long-running scripts.** Raise `max_execution_time` for imports or report generation that takes a while.
- **Restart after changes.** Setting changes apply to running sites only after an **FPM restart**.

## Requirements

PHP management relies on **Homebrew**. If you have not set it up yet, see the [Setup](/guide/setup) guide first.