---
title: Shell is not using the Homebrew Python
description: Fix the "Your shell is not using the Homebrew Python" warning by putting the Homebrew bin directory first in your PATH.
---

# 🐍 Shell is not using the Homebrew Python

If you see this warning on the **Python** screen, the app can manage Python versions through Homebrew, but the `python3` command in your terminal still points somewhere else.

```text
Your shell is not using the Homebrew Python
Current:  /some/other/path/bin/python3
Expected: /opt/homebrew/bin/python3
```

Everything in the app keeps working, but your terminal and the version shown as active can disagree. This guide makes them match.

## 🤔 Why it happens

The app checks which Python your shell resolves by running `command -v python3` in your login shell. Whatever directory comes **first** in your `PATH` wins.

If any other Python sits earlier in your `PATH` than the Homebrew one at `/opt/homebrew/bin/python3`, that version is used instead. Common culprits:

- the **macOS system Python** at `/usr/bin/python3`
- a version manager like **pyenv**
- an **Anaconda/Miniconda** installation
- a Python bundled with another development app

The **Current** path in the warning shows exactly which Python is winning right now.

## 🟢 Step 1 — Activate a version

On the **Python** screen, activate one of the installed versions. This links the selected Homebrew Python into `/opt/homebrew/bin/python3`. If no version is installed yet, install one first, then activate it.

## 🛠️ Step 2 — Put Homebrew first in your PATH

You need the Homebrew bin directory (`/opt/homebrew/bin`) to come before any other Python in your `PATH`.

Open your shell config file. For **zsh** (the macOS default) that is `~/.zshrc`:

```bash
open -e ~/.zshrc
```

Add this line **at the very bottom**, so it takes precedence over entries other tools added earlier:

```bash
export PATH="/opt/homebrew/bin:$PATH"
```

Using **bash** instead? Add the same line to `~/.bash_profile`.

> If a version manager such as **pyenv** or a **conda** installation added its own block to this file, it likely re-points `python3` every time a terminal opens. Keeping the Homebrew line **below** that block ensures Homebrew is found first. You do not need to delete the other entry.

## 🔄 Step 3 — Reload and verify

Apply the change in your current terminal (or just open a new terminal window):

```bash
source ~/.zshrc
```

Confirm `python3` now resolves to Homebrew:

```bash
which python3
# -> /opt/homebrew/bin/python3

python3 --version
```

The path should match the **Expected** path from the warning.

## 🎉 Step 4 — Re-check in the app

Return to the **Python** screen. The app re-checks your shell on load, so reopen the screen (or restart the app). The warning disappears once `which python3` matches `/opt/homebrew/bin/python3`.

## 🆘 Still seeing the warning?

- Make sure you saved the shell config file and opened a **new** terminal.
- Run `echo $PATH` and check that `/opt/homebrew/bin` appears before any other directory containing `python3`.
- If `which python3` still points elsewhere, a version manager like **pyenv** or **conda** is probably re-adding itself later in your config — move the Homebrew line below its block.
- With **conda**, `conda deactivate` (or removing `conda init` lines from your shell config) stops it from overriding the prompt's Python.
