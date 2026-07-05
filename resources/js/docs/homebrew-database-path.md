---
title: Shell is not using the managed database
description: Fix the "Your shell is not using the managed database" warning by putting the Homebrew bin directory first in your PATH.
---

# 🗄️ Shell is not using the managed database

If you see this warning on a **database** screen (MariaDB, MySQL, PostgreSQL or MongoDB), the app manages that database through Homebrew, but the matching command-line client in your terminal still points somewhere else.

```text
Your shell is not using the managed MariaDB
Current:  /some/other/path/bin/mysql
Expected: /opt/homebrew/bin/mysql
```

Everything in the app keeps working — the server still runs on its port — but your terminal's client (and any tooling that relies on it) can use a different build than the one Peermitly manages. This guide makes them match.

## 🔎 Which command is affected?

Each database has its own client binary. The warning shows the exact one in the **Current** line:

- **MariaDB** → `mariadb` / `mysql`
- **MySQL** → `mysql`
- **PostgreSQL** → `psql`
- **MongoDB** → `mongosh`

Wherever this guide uses `mysql`, substitute the binary named in your warning.

## 🤔 Why it happens

The app checks which client your shell resolves by running `command -v mysql` (or `psql`, `mongosh`, …) in your login shell. Whatever directory comes **first** in your `PATH` wins.

If another copy of that client sits earlier in your `PATH` than the Homebrew one in `/opt/homebrew/bin`, that build is used instead — a system client, one shipped by another dev tool, or one from a different package manager. It just needs to appear before Homebrew in your `PATH`.

The **Current** path in the warning shows exactly which binary is winning right now.

## 🛠️ Step 1 — Put Homebrew first in your PATH

You need the Homebrew bin directory (`/opt/homebrew/bin`) to come before any other database client in your `PATH`.

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

## 🔄 Step 2 — Reload and verify

Apply the change in your current terminal (or just open a new terminal window):

```bash
source ~/.zshrc
```

Confirm the client now resolves to Homebrew (use the binary from your warning):

```bash
which mysql
# -> /opt/homebrew/bin/mysql
```

The path should match the **Expected** path from the warning.

## 🎉 Step 3 — Re-check in the app

Return to the database screen. The app re-checks your shell on load, so reopen the screen (or restart the app). The warning disappears once the client resolves to `/opt/homebrew/bin`.

## 🆘 Still seeing the warning?

- Make sure you saved the shell config file and opened a **new** terminal.
- Run `echo $PATH` and check that `/opt/homebrew/bin` appears before any other directory containing the client.
- If it still points elsewhere, that tool may re-add itself to the `PATH` later in your config — move the Homebrew line below it.
- Some tools (for example another Postgres.app or MySQL installer) add their own bin directory on login — those are the usual culprits.
