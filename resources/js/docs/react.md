---
title: React
description: Create a new React site in Peermitly — scaffolded with create-vite, served over HTTPS on a .peer domain with a Vite dev server on a port you choose.
---

# ⚛️ React

Peermitly can scaffold a brand-new **React** app for you and serve it over **HTTPS** on a `.peer` domain. Unlike a PHP site, a React app runs on its own **Vite dev server**; Peermitly puts nginx in front of it as a secure reverse proxy, so `https://your-name.peer` transparently reaches your running dev server — with hot module reload working over the domain.

## ⚙️ How it's built

When you create a React site, Peermitly runs the official **`npm create vite@latest`** scaffolder with the React template, installs the dependencies, and wires everything up. It also patches your `vite.config` automatically so the dev server binds to the right **port**, allows the `.peer` host, and runs HMR over your domain — you don't touch any config by hand.

> React sites need **Node**. Install and activate a Node version first — see [Node versions](/guide/node).

## 🧩 Options

Pick exactly what goes into your project — these map to the create-vite React template and common add-ons:

- **TypeScript** — set the project up with TypeScript.
- **React Compiler** — enable the React Compiler for automatic optimization.
- **React Router** — add client-side routing.
- **Redux Toolkit** — add Redux Toolkit for state management.
- **TanStack Query** — add TanStack Query for server-state and data fetching.
- **Vitest** — set up unit testing with Vitest.
- **ESLint** — add linting.
- **Prettier** — add code formatting.

Choose only the pieces you want; leave the rest off for a leaner setup.

## 🔌 Port

A React site runs on its own port (the Vite dev server). You **set the port** when creating the site — or let Peermitly pick a free one for you (starting from Vite's default `5173`). The port is locked in (`strictPort`), so your app always comes up on the same address, and nginx proxies `your-name.peer` to it.

## 🔒 Served over HTTPS

The site is served over **https** on `your-name.peer` with a trusted local certificate (issued via mkcert). nginx terminates TLS and proxies to your Vite dev server, and **hot module reload** runs securely over the same domain — edits refresh instantly in the browser.

## ▶️ Start the dev server

Because a React app is served by Vite, the dev server has to be running for the site to load:

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
2. Choose **React**.
3. Select the **features** you want (TypeScript, Router, Zustand, …).
4. Set the **name**, the **port** (or accept the suggested free one), and keep **HTTPS** on.
5. Create it. Peermitly scaffolds the app, installs dependencies and configures Vite.
6. Run `npm run dev` in the project, then open `https://your-name.peer`.

![Creating a React site in Peermitly](/images/screenshots/react.png)

## 💡 Notes

- **The dev server must run.** `https://your-name.peer` proxies to Vite — start `npm run dev` to see your app.
- **HMR just works** over the `.peer` domain; no extra configuration needed.
- **Building for production?** React sites are for local development. Deploy the built output (`npm run build`) to your host as usual.
