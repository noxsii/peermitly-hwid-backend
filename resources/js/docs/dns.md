---
title: DNS
description: What DNS does in Peermitly and how to set it up — make your .peer domains resolve to your machine so your local sites open by name.
---

# 🧭 DNS

**DNS** is what makes your `.peer` domains work. It tells your Mac that any address ending in `.peer` — like `my-app.peer` — points to your own machine, so the browser can reach the sites [nginx](/guide/nginx) is serving. You set it up once from the **Services** area, and every `.peer` site works from then on.

## 🧩 What Peermitly uses DNS for

- **Resolves `.peer` domains locally.** Every `*.peer` name points to your machine (`127.0.0.1`), so `my-app.peer` opens your local project instead of the internet.
- **Works for every site automatically.** You never edit your hosts file or add a line per project — one setup covers all current and future `.peer` sites.
- **Pairs with nginx.** DNS gets the browser to your machine by name; nginx then serves the right project over HTTPS.

Behind the scenes Peermitly configures a small local DNS service (**dnsmasq**) and a system resolver rule for the `.peer` domain. You don't have to touch either — the app manages it.

## ✅ Set it up

On the **DNS** screen (open it from the services menu in the header):

1. Click **Set up**.
2. Approve the system prompt if macOS asks for permission — Peermitly needs to add the resolver rule for `.peer`.
3. Wait until DNS shows **Working**.

> Setting up DNS may ask for your macOS password. That's expected: adding the `.peer` resolver rule requires administrator access.

When DNS shows **Working**, it counts toward the green services indicator in the header.

## 🟢 The "Working" status

DNS reports **Working** when a `.peer` address actually resolves to your machine. If it shows **Not set up** or stops working, your `.peer` sites won't open by name — even if nginx is running. Getting DNS to **Working** is the second half of a green header (the first is nginx running).

## 🎛️ Managing DNS

The DNS screen gives you full control:

- **Set up** — install and configure DNS for `.peer`.
- **Restart** — reload DNS after a change or if names stop resolving.
- **Stop** — turn DNS off (`.peer` names stop resolving until you set it up or restart).
- **Update** — upgrade to a newer release when one is available.
- **Uninstall** — remove DNS entirely.

## 📄 Log

DNS keeps its own log, which lists the name lookups it handles. Open it from the DNS screen when you want to see whether a `.peer` name is being resolved, or to debug a lookup that isn't working.

## 🛠️ Troubleshooting

- **DNS shows "Not set up".** Click **Set up** and approve the system prompt.
- **A `.peer` site won't open, but nginx is running.** Open the DNS screen and click **Restart**. Confirm it shows **Working** again.
- **Still not resolving.** Your Mac may be holding an old DNS cache. Flush it, then restart DNS:

```bash
sudo dscacheutil -flushcache
sudo killall -HUP mDNSResponder
```

- **Permission prompt keeps appearing.** DNS setup needs to write a system resolver rule for `.peer`; approving the admin prompt once is required.

## Requirements

DNS is installed and managed for you — you just need Peermitly set up first. See the [Setup](/guide/setup) guide, then the [Starter guide](/guide/starter) to get both nginx and DNS green.
