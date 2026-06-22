<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";

defineOptions({ layout: "" });

const lastUpdated = "June 22, 2026";
</script>

<template>
    <Head title="Privacy — Peermitly" />

    <div class="bg-background text-foreground min-h-screen">
        <header
            class="mx-auto flex max-w-3xl items-center justify-between px-6 py-5"
        >
            <Link href="/" class="text-lg font-semibold tracking-tight"
                >Peermitly</Link
            >
            <Link
                href="/login"
                class="text-muted-foreground hover:text-foreground text-sm"
                >Log in</Link
            >
        </header>

        <main class="mx-auto max-w-3xl px-6 py-16 md:py-24">
            <header class="mb-12 space-y-3">
                <p
                    class="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase"
                >
                    Legal
                </p>
                <h1
                    class="text-foreground text-4xl font-semibold tracking-tight md:text-5xl"
                >
                    Privacy Policy
                </h1>
                <p class="text-muted-foreground text-sm">
                    Last updated: {{ lastUpdated }}
                </p>
            </header>

            <article
                class="prose prose-sm dark:prose-invert text-foreground/90 max-w-none [&_a]:text-primary [&_a]:underline [&_h2]:text-foreground [&_h2]:mt-10 [&_h2]:mb-3 [&_h2]:text-xl [&_h2]:font-semibold [&_h3]:text-foreground [&_h3]:mt-6 [&_h3]:mb-2 [&_h3]:text-base [&_h3]:font-semibold [&_li]:my-1 [&_p]:my-3 [&_p]:leading-7 [&_ul]:my-3 [&_ul]:list-disc [&_ul]:pl-5"
            >
                <h2>1. Controller</h2>
                <p>
                    The operator of Peermitly is responsible for the data
                    processing on this platform. For any privacy-related
                    questions, contact us at
                    <a href="mailto:hello@peermitly.de">hello@peermitly.de</a>.
                </p>

                <h2>2. Overview</h2>
                <p>
                    Peermitly is an HWID spoofer service. We only process the
                    data required to operate the service: account data,
                    technical log data, and the spoof profiles you configure
                    yourself.
                </p>

                <h2>3. Data we process</h2>

                <h3>3.1 Account &amp; authentication</h3>
                <ul>
                    <li>Name, email address, role</li>
                    <li>Hashed password (bcrypt)</li>
                    <li>
                        Optionally registered passkeys (WebAuthn credentials)
                    </li>
                    <li>Session cookies and remember-me tokens for sign-in</li>
                </ul>
                <p>Legal basis: performance of contract (Art. 6(1)(b) GDPR).</p>

                <h3>3.2 Technical log data</h3>
                <ul>
                    <li>
                        Sign-in notifications via email containing IP and user
                        agent to help detect suspicious sign-ins
                    </li>
                    <li>
                        Activity logs for account changes (who changed what)
                    </li>
                </ul>
                <p>
                    Legal basis: legitimate interest in security, abuse
                    detection, and traceability (Art. 6(1)(f) GDPR).
                </p>

                <h2>4. Technologies in use</h2>
                <ul>
                    <li>
                        <strong>Laravel</strong> (PHP 8.4) as the backend
                        framework.
                    </li>
                    <li>
                        <strong>PostgreSQL</strong> as the database for all
                        application data.
                    </li>
                    <li>
                        <strong>Redis</strong> for sessions, cache, and the job
                        queue (Laravel Horizon).
                    </li>
                    <li>
                        <strong>Inertia.js + Vue 3</strong> for the frontend,
                        without any external tracking scripts.
                    </li>
                    <li>
                        <strong>Spatie Passkeys</strong> for passwordless
                        sign-in via WebAuthn.
                    </li>
                </ul>

                <h2>5. Cookies &amp; local storage</h2>
                <p>Only strictly necessary cookies are set:</p>
                <ul>
                    <li>
                        <code>peermitly_session</code> — session cookie that
                        keeps you signed in
                    </li>
                    <li>
                        <code>XSRF-TOKEN</code> — protection against cross-site
                        request forgery
                    </li>
                    <li>
                        <code>remember_web_*</code> — optional, set when
                        "Remember me" is enabled
                    </li>
                </ul>
                <p>
                    No analytics, marketing, or third-party tracking cookies are
                    used.
                </p>

                <h2>6. Third-party services</h2>

                <h3>6.1 Fonts (Bunny Fonts)</h3>
                <p>
                    Fonts are loaded via
                    <a
                        href="https://fonts.bunny.net"
                        target="_blank"
                        rel="noopener noreferrer"
                        >fonts.bunny.net</a
                    >, a privacy-friendly alternative to Google Fonts. IP
                    addresses are not logged there.
                </p>

                <h3>6.2 Email delivery</h3>
                <p>
                    Transactional emails (sign-in alerts, password resets) are
                    sent via our mail provider. Recipient name and email address
                    are processed in the course of delivery.
                </p>

                <h2>7. Retention periods</h2>
                <ul>
                    <li>Account data: until the account is deleted.</li>
                    <li>
                        Activity logs: 12 months, then automatically deleted.
                    </li>
                </ul>

                <h2>8. Your rights</h2>
                <p>You have the right to:</p>
                <ul>
                    <li>Access (Art. 15 GDPR)</li>
                    <li>Rectification (Art. 16 GDPR)</li>
                    <li>Erasure (Art. 17 GDPR)</li>
                    <li>Restriction of processing (Art. 18 GDPR)</li>
                    <li>Data portability (Art. 20 GDPR)</li>
                    <li>
                        Object to processing based on legitimate interests (Art.
                        21 GDPR)
                    </li>
                    <li>
                        Lodge a complaint with a supervisory authority (Art. 77
                        GDPR)
                    </li>
                </ul>

                <h2>9. Security</h2>
                <p>
                    All traffic is encrypted via HTTPS. Passwords are hashed
                    with bcrypt. Passkeys are stored as WebAuthn credentials;
                    your private keys never leave your device.
                </p>

                <h2>10. Changes</h2>
                <p>
                    This policy may be updated when technical or legal
                    conditions change. The current version is always available
                    on this page.
                </p>
            </article>
        </main>

        <footer
            class="border-border/60 text-muted-foreground mx-auto max-w-3xl border-t px-6 py-8 text-sm"
        >
            © {{ new Date().getFullYear() }} Peermitly
        </footer>
    </div>
</template>
