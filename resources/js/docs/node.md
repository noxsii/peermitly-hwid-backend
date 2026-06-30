---
title: Node versions
description: Install, switch and manage Node.js versions in Peermitly — run multiple versions side by side, keep them updated and verify your setup.
---

# 🟩 Node

Peermitly manages **Node.js** for you. You can install several Node versions, switch the active one with a single click, keep them updated and remove the ones you no longer need — all from the **Node** screen under **Binaries**, no terminal required.

## 🧩 What Peermitly does with Node

- **Installs and removes Node versions.**
- **Switches the active version** so the `node` and `npm` commands use the one you pick.
- **Runs multiple versions side by side** — different projects can use different Node versions.
- **Shows available updates** and updates a version with one click.
- **Comes with npm** — installing Node gives you the matching `npm` automatically.

Everything runs **natively** on your machine — no Docker, no virtual machine, and no separate version manager to configure.

## 📥 Install a Node version

On the **Node** screen you see the available Node versions. To add one:

1. Find the version you want in the list.
2. Click **Install**.
3. Peermitly installs it and shows the progress live.

Once finished, the version is ready to activate. You can install as many versions as you need.

> The first install can take a little longer while it is downloaded and linked. Later actions are much faster.

## ✅ Activate the version you want

Only one Node version is the **active** one at a time. The active version is what your terminal's `node` and `npm` commands use.

1. Click **Activate** (or **Default**) on the version you want.
2. Peermitly activates it for your whole system.
3. The version is now marked **Active**.

Verify it in a terminal:

```bash
node -v
npm -v
```

> If your terminal still shows a different Node after activating, your shell `PATH` is pointing somewhere else — often a version manager like **nvm**. See [Shell is not using the Homebrew Node](/guide/homebrew-node-path) to fix it.

## 🔀 Run multiple versions side by side

You don't have to choose just one. Install several versions and switch the active one whenever a project needs a different one — for example, keep an older app on Node 18 while building a new one on the latest LTS.

Switching is instant and non-destructive: your installed versions stay exactly as you left them.

## 🔄 Update a version

When a newer release of an installed version is available, Peermitly marks it with **Update available**. Click **Update** to upgrade it.

## 🗑️ Remove a version

No longer need a version? Click **Remove**. Peermitly uninstalls it. If you remove the currently active version, activate another one afterwards so your `node` and `npm` commands keep working.

## 🧪 Verify everything works

After installing or switching versions, a quick check in your terminal confirms the setup:

```bash
which node
# -> /opt/homebrew/bin/node

node -v
# shows the version you activated

npm -v
# shows the bundled npm version
```

If `which node` does not point to `/opt/homebrew/bin/node`, your shell `PATH` needs adjusting — follow [Shell is not using the Homebrew Node](/guide/homebrew-node-path).

## 💡 Tips

- **Match the project's version.** Activate the Node version your project targets before running `npm install`, so packages build against the right version.
- **Watch out for nvm.** If you have **nvm** installed, it can override the active Node in new terminals. The [Node path guide](/guide/homebrew-node-path) explains how to fix it.
- **npm comes along.** You don't install npm separately — each Node version ships its own matching npm.

## Requirements

Node management needs Peermitly to be set up first. If you have not done that yet, see the [Setup](/guide/setup) guide.