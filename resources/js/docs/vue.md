---
title: Vue
description: Create a new Vue site in Peermitly — scaffolded with create-vue, served over HTTPS on a .peer domain with a Vite dev server on a port you choose.
---

# 💚 Vue

Peermitly can scaffold a brand-new **Vue** app for you and serve it over **HTTPS** on a `.peer` domain. Unlike a PHP site, a Vue app runs on its own **Vite dev server**; Peermitly puts nginx in front of it as a secure reverse proxy, so `https://your-name.peer` transparently reaches your running dev server — with hot module reload working over the domain.

## ⚙️ How it's built

When you create a Vue site, Peermitly runs the official **`npm create vue@latest`** scaffolder, installs the dependencies, and wires everything up. It also patches your `vite.config` automatically so the dev server binds to the right **port**, allows the `.peer` host, and runs HMR over your domain — you don't touch any config by hand.

> Vue sites need **Node**. Install and activate a Node version first — see [Node versions](/guide/node).

## 🧩 Options

Pick exactly what goes into your project — these map to the official create-vue features:

- **TypeScript** — set the project up with TypeScript.
- **JSX** — enable JSX support.
- **Vue Router** — add client-side routing.
- **Pinia** — add Pinia for state management.
- **Vitest** — set up unit testing with Vitest.
- **ESLint** — add linting.
- **Prettier** — add code formatting.
- **Vue DevTools** — include the Vue DevTools plugin.

Choose only the pieces you want; leave the rest off for a leaner setup.

## 🔌 Port

A Vue site runs on its own port (the Vite dev server). You **set the port** when creating the site — or let Peermitly pick a free one for you (starting from Vite's default `5173`). The port is locked in (`strictPort`), so your app always comes up on the same address, and nginx proxies `your-name.peer` to it.

## 🔒 Served over HTTPS

The site is served over **https** on `your-name.peer` with a trusted local certificate (issued via mkcert). nginx terminates TLS and proxies to your Vite dev server, and **hot module reload** runs securely over the same domain — edits refresh instantly in the browser.

## ▶️ Start the dev server

Because a Vue app is served by Vite, the dev server has to be running for the site to load:

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
2. Choose **Vue**.
3. Select the **features** you want (TypeScript, Router, Pinia, …).
4. Set the **name**, the **port** (or accept the suggested free one), and keep **HTTPS** on.
5. Create it. Peermitly scaffolds the app, installs dependencies and configures Vite.
6. Run `npm run dev` in the project, then open `https://your-name.peer`.

![Creating a Vue site in Peermitly](/images/screenshots/vue.png)

## 💡 Notes

- **The dev server must run.** `https://your-name.peer` proxies to Vite — start `npm run dev` to see your app.
- **HMR just works** over the `.peer` domain; no extra configuration needed.
- **Building for production?** Vue sites are for local development. Deploy the built output (`npm run build`) to your host as usual.
