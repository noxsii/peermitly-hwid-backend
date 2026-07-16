---
title: Next.js
description: Create a new Next.js site in Peermitly — scaffolded with create-next-app, served over HTTPS on a .peer domain with a dev server on a port you choose.
---

# ▲ Next.js

Peermitly can scaffold a brand-new **Next.js** app for you and serve it over **HTTPS** on a `.peer` domain. Like Vue and React, a Next.js app runs on its own **dev server**; Peermitly puts nginx in front of it as a secure reverse proxy, so `https://your-name.peer` transparently reaches your running dev server — with Fast Refresh working over the domain.

> **Free for everyone.** Next.js sites are available on every plan — no Pro license required.

## ⚙️ How it's built

When you create a Next.js site, Peermitly runs the official **`npx create-next-app@latest`** scaffolder with the options you selected and installs the dependencies. It also configures the project automatically: the dev script is wired to your chosen **port**, and the `.peer` domain is added to `allowedDevOrigins` in `next.config` — you don't touch any config by hand.

> Next.js sites need **Node**. Install and activate a Node version first — see [Node versions](/guide/node).

## 🧩 Options

Pick what the scaffolder should set up — Peermitly passes your choices straight to `create-next-app`:

- **TypeScript** _(on by default)_ — a TypeScript project instead of plain JavaScript.
- **Tailwind CSS** _(on by default)_ — Tailwind preconfigured and ready to use.
- **ESLint** _(on by default)_ — linting set up out of the box.
- **App Router** _(on by default)_ — the modern `app/` directory routing. Turn it off for the classic Pages Router.
- **src Directory** — keep your application code under `src/`.
- **Turbopack** _(on by default)_ — the faster Rust-based dev bundler.

Choose only the pieces you want; leave the rest off for a leaner setup.

## 🔧 Git

Next.js sites can start as a **Git repository**. Turn Git on to have Peermitly initialize the repo during scaffolding, or leave it off and set up version control yourself later.

## 🔌 Port

A Next.js site runs on its own port (the dev server). You **set the port** when creating the site — or let Peermitly pick a free one for you (starting from Next's default `3000`). The port is locked in, so your app always comes up on the same address, and nginx proxies `your-name.peer` to it.

## 🔒 Served over HTTPS

The site is served over **https** on `your-name.peer` with a trusted local certificate (issued via mkcert). nginx terminates TLS and proxies to your Next.js dev server, and **Fast Refresh** runs securely over the same domain — edits refresh instantly in the browser.

## ▶️ Start the dev server

Because a Next.js app is served by its dev server, it has to be running for the site to load:

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
2. Choose **Next.js**.
3. Select your **options** (TypeScript, Tailwind, App Router, …) and whether to start a **Git** repository.
4. Set the **name**, the **port** (or accept the suggested free one), and keep **HTTPS** on.
5. Create it. Peermitly scaffolds the app, installs dependencies and configures Next.js.
6. Run `npm run dev` in the project, then open `https://your-name.peer`.

## 💡 Notes

- **The dev server must run.** `https://your-name.peer` proxies to the Next.js dev server — start `npm run dev` to see your app.
- **Fast Refresh just works** over the `.peer` domain; `allowedDevOrigins` is preconfigured, no extra setup needed.
- **Building for production?** Next.js sites are for local development. Deploy the built output (`npm run build`) to your host as usual.
