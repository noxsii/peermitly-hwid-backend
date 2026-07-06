---
title: Python versions
description: Install, switch and manage Python versions in Peermitly — run multiple versions side by side, keep them updated and verify your setup.
---

# 🐍 Python

Peermitly manages **Python** for you. You can install several Python versions, switch the active one with a single click, keep them updated and remove the ones you no longer need — all from the **Python** screen under **Binaries**, no terminal required.

## 🧩 What Peermitly does with Python

- **Installs and removes Python versions.**
- **Switches the active version** so the `python3` and `pip3` commands use the one you pick.
- **Runs multiple versions side by side** — different projects can use different Python versions.
- **Shows available updates** and updates a version with one click.
- **Comes with pip** — installing Python gives you the matching `pip` automatically.

Everything runs **natively** on your machine — no Docker, no virtual machine, and no separate version manager to configure.

## 📥 Install a Python version

On the **Python** screen you see the available Python versions. To add one:

1. Find the version you want in the list.
2. Click **Install**.
3. Peermitly installs it and shows the progress live.

Once finished, the version is ready to activate. You can install as many versions as you need.

> The first install can take a little longer while it is downloaded and linked. Later actions are much faster.

## ✅ Activate the version you want

Only one Python version is the **active** one at a time. The active version is what your terminal's `python3` and `pip3` commands use.

1. Click **Activate** (or **Default**) on the version you want.
2. Peermitly activates it for your whole system.
3. The version is now marked **Active**.

Verify it in a terminal:

```bash
python3 --version
pip3 --version
```

> If your terminal still shows a different Python after activating, your shell `PATH` is pointing somewhere else — often a version manager like **pyenv** or the macOS system Python. See [Shell is not using the Homebrew Python](/guide/homebrew-python-path) to fix it.

## 🔀 Run multiple versions side by side

You don't have to choose just one. Install several versions and switch the active one whenever a project needs a different one — for example, keep an older app on Python 3.11 while building a new one on the latest release.

Switching is instant and non-destructive: your installed versions stay exactly as you left them.

## 🔄 Update a version

When a newer release of an installed version is available, Peermitly marks it with **Update available**. Click **Update** to upgrade it.

## 🗑️ Remove a version

No longer need a version? Click **Remove**. Peermitly uninstalls it. If you remove the currently active version, activate another one afterwards so your `python3` and `pip3` commands keep working.

## 🧪 Verify everything works

After installing or switching versions, a quick check in your terminal confirms the setup:

```bash
which python3
# -> /opt/homebrew/bin/python3

python3 --version
# shows the version you activated

pip3 --version
# shows the bundled pip version
```

If `which python3` does not point to `/opt/homebrew/bin/python3`, your shell `PATH` needs adjusting — follow [Shell is not using the Homebrew Python](/guide/homebrew-python-path).

## 💡 Tips

- **Match the project's version.** Activate the Python version your project targets before creating a virtual environment or running `pip install`, so packages build against the right version.
- **Use virtual environments per project.** Create one with `python3 -m venv .venv` — it is pinned to the Python version that was active when you created it.
- **Watch out for pyenv and conda.** If either is installed, it can override the active Python in new terminals. The [Python path guide](/guide/homebrew-python-path) explains how to fix it.
- **pip comes along.** You don't install pip separately — each Python version ships its own matching pip.

## Requirements

Python management needs Peermitly to be set up first. If you have not done that yet, see the [Setup](/guide/setup) guide.
