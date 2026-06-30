---
title: Introduction
description: Peermitly is a fast, beautiful local development environment for every stack — PHP, Node, Python, Ruby and Go.
---

# 👋 Introduction

Peermitly is a blazing-fast local development environment for **macOS, Windows and Linux**. Drop a project into your folder and it is instantly served on a secure `.peer` domain — no config files, no containers, no terminal gymnastics.

## 🔥 Why Peermitly

Most local setups force a choice between speed and convenience. Docker is portable but slow to boot and heavy on memory. Hand-rolled setups are fast but brittle and painful to share across a team. Peermitly gives you both: native performance with zero configuration.

- **Instant sites** — a project becomes a live `.peer` URL in under a second.
- **Every stack, one app** — PHP, Node, Python, Ruby and Go side by side.
- **Native speed** — services run directly on your machine, not in a VM.
- **Secure by default** — automatic local HTTPS with trusted certificates.

## ⚙️ How it works

Peermitly watches your projects directory and detects each project's stack. It wires up the right runtime, a web server, databases and mail catching, then exposes everything on a predictable local domain.

```bash
# Your project folder
~/code/my-app        ->  https://my-app.peer
~/code/api-service   ->  https://api-service.peer
```

You keep working the way you already do — Peermitly handles the plumbing.
