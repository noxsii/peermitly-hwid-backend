---
title: IDE integration
description: Open any site directly in your favorite editor. Pick your preferred IDE once in Settings and every site gets an "Open in …" button — VS Code, PhpStorm, Cursor, Zed and many more.
---

# 💻 IDE integration

Peermitly can open any of your sites directly in the editor you already use. Pick your **preferred IDE once** in the settings, and every site in the **Sites** list gets its own **Open in …** button — one click and the project folder opens in your editor, ready to code.

## ✨ Supported editors

Peermitly supports all the popular editors on macOS:

- **VS Code**
- **Cursor**
- **PhpStorm**
- **WebStorm**
- **IntelliJ IDEA**
- **Sublime Text**
- **Zed**
- **Nova**
- **Neovim**
- **Fleet**
- **Windsurf**

## ⚙️ Choose your preferred IDE

1. Open **Settings** and stay on the **General** tab.
2. Under **Preferred IDE**, pick your editor from the dropdown.
3. That's it — the choice is saved automatically.

You only do this once. From now on, Peermitly uses this editor for all sites.

## 🚀 Open a site in your IDE

Go to **Sites**. Every site row now shows an **Open in …** button next to the existing **Open** (browser) and folder buttons — for example **Open in VS Code** or **Open in PhpStorm**, depending on what you picked.

Click it and Peermitly launches your editor with the site's project folder. No terminal, no drag and drop, no hunting for the folder in Finder.

> **Tip:** The button only appears once you've chosen a preferred IDE in Settings. If you don't see it, set your editor first.

## 🖥️ A note on Neovim

Neovim is a terminal editor, so it works a little differently: Peermitly opens a new **Terminal** window and starts `nvim` in your project folder for you.

## 🧯 Troubleshooting

If something goes wrong, Peermitly shows a short message right next to the button:

- **"… could not be opened. Is it installed?"** — Your selected editor isn't installed (or macOS can't find it). Install the editor or pick a different one in **Settings → General → Preferred IDE**.
- **"Project folder no longer exists."** — The site's folder was moved or deleted on disk. Check the site's path or remove and re-add the site.

## 💡 Notes

- **Free for everyone.** The IDE integration is available on every plan — no Pro license required.
- **One editor for all sites.** The preferred IDE is a global setting; changing it updates the button on every site.
- **Switching is easy.** You can change your preferred IDE at any time in **Settings → General** — the buttons update immediately.
