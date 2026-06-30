---
title: Shell is not using the Homebrew Node
description: Fix the "Your shell is not using the Homebrew Node" warning by putting the Homebrew bin directory first in your PATH.
---

# Shell is not using the Homebrew Node

If you see this warning on the **Node** screen, the app can manage Node versions through Homebrew, but the `node` command in your terminal still points somewhere else.

```text
Your shell is not using the Homebrew Node
Current:  /some/other/path/bin/node
Expected: /opt/homebrew/bin/node
```

Everything in the app keeps working, but your terminal and the version shown as active can disagree. This guide makes them match.

## Why it happens

The app checks which Node your shell resolves by running `command -v node` in your login shell. Whatever directory comes **first** in your `PATH` wins.

If any other Node sits earlier in your `PATH` than the Homebrew one at `/opt/homebrew/bin/node`, that version is used instead. This is often a Node managed by another tool — for example a version manager like **nvm**, or one bundled with another development app — whatever it is, it just needs to appear before Homebrew in your `PATH`.

The **Current** path in the warning shows exactly which Node is winning right now.

## Step 1 — Activate a version

On the **Node** screen, activate one of the installed versions. This links the selected Homebrew Node into `/opt/homebrew/bin/node`. If no version is installed yet, install one first, then activate it.

## Step 2 — Put Homebrew first in your PATH

You need the Homebrew bin directory (`/opt/homebrew/bin`) to come before any other Node in your `PATH`.

Open your shell config file. For **zsh** (the macOS default) that is `~/.zshrc`:

```bash
open -e ~/.zshrc
```

Add this line **at the very bottom**, so it takes precedence over entries other tools added earlier:

```bash
export PATH="/opt/homebrew/bin:$PATH"
```

Using **bash** instead? Add the same line to `~/.bash_profile`.

> If a version manager such as **nvm** added its own block to this file, it likely re-points `node` every time a terminal opens. Keeping the Homebrew line **below** that block ensures Homebrew is found first. You do not need to delete the other entry.

## Step 3 — Reload and verify

Apply the change in your current terminal (or just open a new terminal window):

```bash
source ~/.zshrc
```

Confirm `node` now resolves to Homebrew:

```bash
which node
# -> /opt/homebrew/bin/node

node -v
```

The path should match the **Expected** path from the warning.

## Step 4 — Re-check in the app

Return to the **Node** screen. The app re-checks your shell on load, so reopen the screen (or restart the app). The warning disappears once `which node` matches `/opt/homebrew/bin/node`.

## Still seeing the warning?

- Make sure you saved the shell config file and opened a **new** terminal.
- Run `echo $PATH` and check that `/opt/homebrew/bin` appears before any other directory containing `node`.
- If `which node` still points elsewhere, a version manager like **nvm** is probably re-adding itself later in your config — move the Homebrew line below its block.