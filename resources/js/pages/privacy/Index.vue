<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import LogoMark from "@/components/Logo.vue";

defineOptions({ layout: "" });

const lastUpdated = "July 17, 2026";
</script>

<template>
    <Head title="Privacy — Peermitly" />

    <div class="bg-background text-foreground min-h-screen">
        <header
            class="mx-auto flex max-w-3xl items-center justify-between px-6 py-5"
        >
            <Link href="/" class="flex items-center gap-2.5">
                <LogoMark size="size-9" />
                <span class="text-lg font-semibold tracking-tight"
                    >Peermitly</span
                >
            </Link>
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
                <h2>1. Who we are</h2>
                <p>
                    Peermitly provides software that lets you manage your own
                    hardware identity. This policy explains what data we process
                    when you use our website and software. For any privacy
                    question, contact us at
                    <a href="mailto:hello@peermitly.de">hello@peermitly.de</a>.
                </p>

                <h2>2. Overview</h2>
                <p>
                    We keep data collection to the minimum needed to run the
                    service: the account you create, your purchase and license
                    status, and basic security information. We do not sell your
                    data, and we do not track you across other sites.
                </p>

                <h2>3. Data we process</h2>

                <h3>3.1 Account</h3>
                <ul>
                    <li>Name and email address</li>
                    <li>An encrypted password and, optionally, a passkey</li>
                    <li>Your license and subscription status</li>
                </ul>
                <p>We use this to create and operate your account.</p>

                <h3>3.2 Security data</h3>
                <ul>
                    <li>
                        Sign-in alerts so you notice access you did not make
                    </li>
                    <li>A limited record of changes made to your account</li>
                </ul>
                <p>We use this to protect your account and prevent abuse.</p>

                <h3>3.3 Device binding</h3>
                <p>
                    When you sign in through the Peermitly application, the
                    application sends a device identifier (HWID) to our servers.
                    On the first successful sign-in, this identifier is stored
                    with your account. On later sign-ins, the submitted
                    identifier is compared with the stored value to ensure that
                    the account is being used on the authorized device.
                </p>
                <p>
                    We process the HWID to provide the device-binding feature
                    you request and to protect accounts and our service against
                    unauthorized access and abuse. The legal bases are Art. 6
                    (1) (b) GDPR (performance of the service) and Art. 6 (1) (f)
                    GDPR (our legitimate interest in account and service
                    security). We do not use the identifier for advertising or
                    cross-service tracking.
                </p>
                <p>
                    The HWID is stored until it is reset or replaced for your
                    account, or until your account is deleted. When the account
                    is deleted, the associated HWID is deleted with it, unless
                    we are legally required to retain specific information.
                </p>

                <h3>3.4 What we do not collect</h3>
                <p>
                    Apart from the HWID used for device binding as described
                    above, the software runs locally on your device. We do not
                    upload or store the values generated by the software, your
                    files, or any game data. That information does not leave
                    your computer through Peermitly.
                </p>

                <h2>4. Cookies</h2>
                <p>
                    We only use cookies that are strictly necessary to keep you
                    signed in and to secure forms. We do not use advertising,
                    analytics, or third-party tracking cookies.
                </p>

                <h2>5. Service providers</h2>
                <p>
                    We rely on a small number of trusted providers to deliver
                    email (such as sign-in alerts and password resets) and to
                    process payments. They only receive the data needed for
                    their specific task and may not use it for anything else.
                </p>

                <h2>6. Retention</h2>
                <ul>
                    <li>Account data: kept until you delete your account.</li>
                    <li>
                        Security records: kept for a limited period, then
                        deleted automatically.
                    </li>
                </ul>

                <h2>7. Your rights</h2>
                <p>You have the right to:</p>
                <ul>
                    <li>Access your data (Art. 15 GDPR)</li>
                    <li>Correct it (Art. 16 GDPR)</li>
                    <li>Delete it (Art. 17 GDPR)</li>
                    <li>Restrict processing (Art. 18 GDPR)</li>
                    <li>Receive a copy (Art. 20 GDPR)</li>
                    <li>Object to processing (Art. 21 GDPR)</li>
                    <li>Complain to a supervisory authority (Art. 77 GDPR)</li>
                </ul>

                <h2>8. Security</h2>
                <p>
                    All traffic is encrypted in transit. Passwords are stored
                    only in hashed form, and passkeys never leave your device.
                    We apply appropriate safeguards to protect your data.
                </p>

                <h2>9. Changes</h2>
                <p>
                    We may update this policy when the service or the law
                    changes. The current version is always available on this
                    page.
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
