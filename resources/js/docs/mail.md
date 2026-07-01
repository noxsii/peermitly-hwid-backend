---
title: Mail
description: Catch and inspect the emails your local apps send with Peermitly's built-in Mailpit mail server — nothing leaves your machine.
---

# 📬 Mail

The **Mail** service is a local mail catcher built on **Mailpit**. Every email your apps try to send during development is captured here instead of being delivered to a real inbox — so you can build and test sign-ups, password resets, receipts and notifications without ever sending a real message.

## 🧩 What Peermitly uses Mail for

- **Catches all outgoing mail.** Your app sends email as usual; Mailpit receives it and holds it locally.
- **Nothing is delivered for real.** No message ever leaves your machine — safe to test with real-looking addresses.
- **Lets you inspect every message.** Read the HTML and plain-text versions, view headers, see the raw source and open attachments.
- **Keeps a searchable inbox** of everything your apps have sent.

## 🔌 How it works

Mailpit runs two local endpoints:

| Endpoint   | Address          | Purpose                                    |
| ---------- | ---------------- | ------------------------------------------ |
| **SMTP**   | `127.0.0.1:1025` | Your app sends mail here                   |
| **Web UI** | `127.0.0.1:8025` | View caught mail (also visible in the app) |

Your app talks to the SMTP endpoint; Mailpit stores what it receives and shows it in the inbox. The SMTP port defaults to **1025** and can be changed (see below).

## ✅ Install and start

On the **Mail** screen (open it from the services menu in the header):

1. Click **Install** and wait for it to finish.
2. Click **Start**.

Once running, Mailpit is ready to catch mail on `127.0.0.1:1025`.

## ⚙️ Point your app at Mailpit

Configure your app's mail settings to send through Mailpit's SMTP endpoint. There is **no username, password or encryption** — it's a local catcher.

- **Host:** `127.0.0.1`
- **Port:** `1025`
- **Username / Password:** none
- **Encryption / TLS:** none

For a **Laravel** project, your `.env`:

```ini
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.test"
MAIL_FROM_NAME="My App"
```

Other frameworks work the same way — point their SMTP host to `127.0.0.1` and the port to `1025`, with no auth.

## 📥 The inbox

Every message your apps send appears in the Mail inbox. For each one you can:

- **Read it** — switch between the HTML and plain-text versions.
- **View headers** — inspect `From`, `To`, `Subject` and all the technical headers.
- **See the raw source** — the complete raw message exactly as it was sent.
- **Open attachments** — any files the email carried.
- **Delete** a single message, or **Clear** the whole inbox to start fresh.

![The Mail inbox in Peermitly](/images/screenshots/mail.png)

## 🔀 Change the SMTP port

If port `1025` is already in use, or your app expects a different port, change it on the **Mail** screen with **Set port**. Update your app's mail port to match, then restart the service.

## 🎛️ Managing the service

- **Start / Stop** — turn the mail catcher on or off.
- **Restart** — apply a port change or recover the service.
- **Update** — upgrade Mailpit to a newer release when available.
- **Uninstall** — remove it entirely.

## 🛠️ Troubleshooting

- **My app's mail isn't showing up.** Check that the Mail service is **running**, and that your app's mail **port matches** the one on the Mail screen (default `1025`) with host `127.0.0.1`.
- **Connection refused when sending.** The service is stopped — start it from the Mail screen.
- **Wrong port after changing it.** Update your app's mail port to the new value and restart the service.
- **Emails send for real instead of being caught.** Your app is still using a real mail provider — switch its mail settings to the SMTP values above.

## Requirements

The Mail service is installed and managed for you — you just need Peermitly set up first. See the [Setup](/guide/setup) guide.
