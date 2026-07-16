---
title: Nuxt
description: Create a new Nuxt site in Peermitly — scaffolded with create-nuxt, served over HTTPS on a .peer domain with a dev server on a port you choose.
---

# ⛰️ Nuxt

Peermitly can scaffold a brand-new **Nuxt** app for you and serve it over **HTTPS** on a `.peer` domain. Like Vue and React, a Nuxt app runs on its own **dev server** (Nuxt uses Vite under the hood); Peermitly puts nginx in front of it as a secure reverse proxy, so `https://your-name.peer` transparently reaches your running dev server — with hot module reload working over the domain.

> **Free for everyone.** Nuxt sites are available on every plan — no Pro license required.

## ⚙️ How it's built

When you create a Nuxt site, Peermitly runs the official **`npm create nuxt@latest`** scaffolder, installs the dependencies, and adds any modules you selected. It also patches your `nuxt.config` automatically so the dev server binds to the right **port**, allows the `.peer` host, and runs HMR over your domain — you don't touch any config by hand.

> Nuxt sites need **Node**. Install and activate a Node version first — see [Node versions](/guide/node).

## 🧩 Modules

Pick the official Nuxt modules to include — Peermitly wires them into the project during setup:

- **ESLint** — add linting.
- **Nuxt UI** — the official Nuxt UI component library.
- **Content** — file-based content management (Markdown, YAML, …).
- **Image** — optimized, responsive images.
- **Fonts** — automatic web-font handling.
- **Icon** — thousands of icons on demand.
- **Scripts** — load third-party scripts the performant way.
- **Test Utils** — the official testing helpers for Nuxt.

Choose only the pieces you want; leave the rest off for a leaner setup.

## 🔧 Git

Nuxt sites can start as a **Git repository**. Turn Git on to have Peermitly initialize the repo during scaffolding, or leave it off and set up version control yourself later.

## 🔌 Port

A Nuxt site runs on its own port (the dev server). You **set the port** when creating the site — or let Peermitly pick a free one for you (starting from Nuxt's default `3000`). The port is locked in, so your app always comes up on the same address, and nginx proxies `your-name.peer` to it.

## 🔒 Served over HTTPS

The site is served over **https** on `your-name.peer` with a trusted local certificate (issued via mkcert). nginx terminates TLS and proxies to your Nuxt dev server, and **hot module reload** runs securely over the same domain — edits refresh instantly in the browser.

## ▶️ Start the dev server

Because a Nuxt app is served by its dev server, it has to be running for the site to load:

```bash
cd your-project
npm run dev
```

With the dev server up, open `https://your-name.peer`. If the dev server is **not** running, Peermitly shows a small offline page instead of an error — start `npm run dev` and reload.

## ✅ Before you start

- A **Node** version installed and active — see [Node versions](/guide/node).
- The services indicator **green**: **nginx** running and **DNS** working. If not, do the [Starter guide](/guide/starter) first.

## 🚀 Create the site

1. Open **Sites** and start a new site.
2. Choose **Nuxt**.
3. Select the **modules** you want (ESLint, Nuxt UI, Content, …) and whether to start a **Git** repository.
4. Set the **name**, the **port** (or accept the suggested free one), and keep **HTTPS** on.
5. Create it. Peermitly scaffolds the app, installs dependencies and configures Nuxt.
6. Run `npm run dev` in the project, then open `https://your-name.peer`.

![Creating a Nuxt site in Peermitly](/images/screenshots/nuxt.png)

## 💡 Notes

- **The dev server must run.** `https://your-name.peer` proxies to the Nuxt dev server — start `npm run dev` to see your app.
- **HMR just works** over the `.peer` domain; no extra configuration needed.
- **Building for production?** Nuxt sites are for local development. Deploy the built output (`npm run build`) to your host as usual.
