---
title: Astro
description: Create a new Astro site in Peermitly — scaffolded with create-astro, served over HTTPS on a .peer domain with a Vite dev server on a port you choose.
---

# 🚀 Astro

Peermitly can scaffold a brand-new **Astro** app for you and serve it over **HTTPS** on a `.peer` domain. Like Vue and React, an Astro app runs on its own **dev server** (Astro uses Vite under the hood); Peermitly puts nginx in front of it as a secure reverse proxy, so `https://your-name.peer` transparently reaches your running dev server — with hot module reload working over the domain.

> **Free for everyone.** Astro sites are available on every plan — no Pro license required.

## ⚙️ How it's built

When you create an Astro site, Peermitly runs the official **`npm create astro@latest`** scaffolder with the template you pick, installs the dependencies, and adds any UI integrations you selected. It also patches your `astro.config` automatically so the dev server binds to the right **port**, allows the `.peer` host, and runs HMR over your domain — you don't touch any config by hand.

> Astro sites need **Node**. Install and activate a Node version first — see [Node versions](/guide/node).

## 📦 Starter template

Choose the template your project starts from — these map to the official create-astro templates:

- **Basics** — a minimal starter with the standard Astro layout.
- **Blog** — a ready-made blog with content collections.
- **Starlight** — Astro's documentation-site theme.
- **Minimal** — an empty project with nothing but the essentials.

## 🧩 Integrations

Add optional integrations — Peermitly runs `astro add` for the ones you select:

- **React** — use React components in your Astro pages.
- **Vue** — use Vue components in your Astro pages.
- **Svelte** — use Svelte components in your Astro pages.
- **Solid** — use SolidJS components in your Astro pages.
- **Tailwind CSS** — add Tailwind CSS styling.
- **MDX** — write pages in MDX (Markdown + components).
- **Sitemap** — generate a sitemap automatically.
- **Partytown** — offload third-party scripts to a web worker.

Choose only the pieces you want; leave the rest off for a leaner setup.

## 🔌 Port

An Astro site runs on its own port (the dev server). You **set the port** when creating the site — or let Peermitly pick a free one for you (starting from Astro's default `4321`). The port is locked in (`strictPort`), so your app always comes up on the same address, and nginx proxies `your-name.peer` to it.

## 🔒 Served over HTTPS

The site is served over **https** on `your-name.peer` with a trusted local certificate (issued via mkcert). nginx terminates TLS and proxies to your dev server, and **hot module reload** runs securely over the same domain — edits refresh instantly in the browser.

## ▶️ Start the dev server

Because an Astro app is served by its dev server, it has to be running for the site to load:

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
2. Choose **Astro**.
3. Pick a **template** (Basics, Blog, Starlight, Minimal).
4. Select the **integrations** you want (React, Tailwind CSS, MDX, …).
5. Set the **name**, the **port** (or accept the suggested free one), and keep **HTTPS** on.
6. Create it. Peermitly scaffolds the app, installs dependencies and configures Astro.
7. Run `npm run dev` in the project, then open `https://your-name.peer`.

![Creating an Astro site in Peermitly](/images/screenshots/astro.png)

## 💡 Notes

- **The dev server must run.** `https://your-name.peer` proxies to the Astro dev server — start `npm run dev` to see your app.
- **HMR just works** over the `.peer` domain; no extra configuration needed.
- **Building for production?** Astro sites are for local development. Deploy the built output (`npm run build`) to your host as usual.
