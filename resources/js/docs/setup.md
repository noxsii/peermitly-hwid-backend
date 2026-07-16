---
title: Setup
description: What you need before running Peermitly — download the app and install Homebrew, Composer and mkcert.
---

# 🚀 Setup

Before Peermitly can serve your projects, a few command-line tools must be present on your Mac: **Homebrew**, **Composer** and **mkcert**. This page walks you through getting the app and installing each one.

## 📥 1. Download the app

After checkout, your download and license appear in your dashboard on the start page. Download Peermitly there, move it to your **Applications** folder, and open it.

> The very first version is installed manually. After that, [Auto-update](/guide/auto-update) keeps the app up to date automatically — the one Pro feature. Without Pro you simply update manually the same way you installed it.

## 🍺 2. Install Homebrew (required first)

Homebrew is the package manager Peermitly uses to install and manage PHP, Node, Composer and mkcert. Everything else depends on it, so install Homebrew **first**.

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Verify it is available:

```bash
brew --version
```

Make sure `/opt/homebrew/bin` comes first in your `PATH`. If the app later warns that your shell is not using the Homebrew PHP or Node, follow the matching troubleshooting guide.

## 🎼 3. Install Composer

You only need Composer if you work on **PHP projects** such as Laravel or Symfony — it's the package manager those frameworks use. Building only with Node, static sites or other stacks? You can skip this step.

Composer is required to create and manage PHP projects.

```bash
brew install composer
```

Verify:

```bash
composer --version
```

> Peermitly can install Composer for you the first time it needs it, but installing it up front avoids surprises.

## 🔐 4. Install mkcert

mkcert issues the locally-trusted TLS certificates that let Peermitly serve your sites over **https** on secure local domains.

```bash
brew install mkcert
mkcert -install
```

`mkcert -install` adds a local certificate authority to your system trust store — you only need to run it once. Verify:

```bash
mkcert -version
```

## ✅ You're ready

Open Peermitly. It detects the installed tools, lets you activate PHP and Node versions, and create sites. If anything is missing, the app will point you to the tool you still need to install.
