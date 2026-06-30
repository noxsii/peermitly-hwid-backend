---
title: Shell is not using the Homebrew PHP
description: Fix the "Your shell is not using the Homebrew PHP" warning by putting the Homebrew bin directory first in your PATH.
---

# 🐘 Shell is not using the Homebrew PHP

If you see this warning on the **PHP** screen, the app can manage PHP versions through Homebrew, but the `php` command in your terminal still points somewhere else.

```text
Your shell is not using the Homebrew PHP
Current:  /some/other/path/bin/php
Expected: /opt/homebrew/bin/php
```

Everything in the app keeps working, but your terminal and the version shown as active can disagree. This guide makes them match.

## 🤔 Why it happens

The app checks which PHP your shell resolves by running `command -v php` in your login shell. Whatever directory comes **first** in your `PATH` wins.

If any other PHP sits earlier in your `PATH` than the Homebrew one at `/opt/homebrew/bin/php`, that version is used instead. This can be a system PHP, a PHP shipped by another development tool, or one installed by a different version manager — whatever it is, it just needs to appear before Homebrew in your `PATH`.

The **Current** path in the warning shows exactly which PHP is winning right now.

## 🟢 Step 1 — Activate a version

On the **PHP** screen, activate one of the installed versions. This links the selected Homebrew PHP into `/opt/homebrew/bin/php`. If no version is installed yet, install one first, then activate it.

## 🛠️ Step 2 — Put Homebrew first in your PATH

You need the Homebrew bin directory (`/opt/homebrew/bin`) to come before any other PHP in your `PATH`.

Open your shell config file. For **zsh** (the macOS default) that is `~/.zshrc`:

```bash
open -e ~/.zshrc
```

Add this line **at the very bottom**, so it takes precedence over entries other tools added earlier:

```bash
export PATH="/opt/homebrew/bin:$PATH"
```

Using **bash** instead? Add the same line to `~/.bash_profile`.

> If another tool added its own PATH line to this file, leaving the Homebrew line at the bottom ensures Homebrew is found first. You do not need to delete the other entry.

## 🔄 Step 3 — Reload and verify

Apply the change in your current terminal (or just open a new terminal window):

```bash
source ~/.zshrc
```

Confirm `php` now resolves to Homebrew:

```bash
which php
# -> /opt/homebrew/bin/php

php -v
```

The path should match the **Expected** path from the warning.

## 🎉 Step 4 — Re-check in the app

Return to the **PHP** screen. The app re-checks your shell on load, so reopen the screen (or restart the app). The warning disappears once `which php` matches `/opt/homebrew/bin/php`.

## 🆘 Still seeing the warning?

- Make sure you saved the shell config file and opened a **new** terminal.
- Run `echo $PATH` and check that `/opt/homebrew/bin` appears before any other directory containing `php`.
- If `which php` still points elsewhere, that tool may re-add itself to the `PATH` later in your config — move the Homebrew line below it.
