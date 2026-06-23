<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import LogoMark from "@/components/Logo.vue";

defineOptions({ layout: "" });
</script>

<template>
    <Head title="Imprint — Peermitly" />

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
                    Imprint
                </h1>
                <p class="text-muted-foreground text-sm">
                    Information pursuant to § 5 DDG
                </p>
            </header>

            <article
                class="prose prose-sm dark:prose-invert text-foreground/90 max-w-none [&_a]:text-primary [&_a]:underline [&_h2]:text-foreground [&_h2]:mt-10 [&_h2]:mb-3 [&_h2]:text-xl [&_h2]:font-semibold [&_h3]:text-foreground [&_h3]:mt-6 [&_h3]:mb-2 [&_h3]:text-base [&_h3]:font-semibold [&_li]:my-1 [&_p]:my-3 [&_p]:leading-7 [&_ul]:my-3 [&_ul]:list-disc [&_ul]:pl-5"
            >
                <h2>Service provider</h2>
                <p>
                    <!-- Name / company + legal form -->
                    <br />
                    <!-- Street, number -->
                    <br />
                    <!-- Postal code, city -->
                    <br />
                    <!-- Country -->
                </p>

                <h2>Contact</h2>
                <p>
                    Phone: <br />
                    Email: <br />
                    Web: <br />
                </p>

                <h2>Represented by</h2>
                <p>
                    <!-- Authorised representative (e.g. managing director) -->
                </p>

                <h2>Register entry</h2>
                <p>
                    Registering court: <br />
                    Registration number: <br />
                </p>

                <h2>VAT ID</h2>
                <p>
                    VAT identification number pursuant to § 27 a of the German
                    VAT Act: <br />
                </p>

                <h2>Responsible for the content under § 18 (2) MStV</h2>
                <p>
                    <!-- Name -->
                    <br />
                    <!-- Address -->
                </p>

                <h2>EU dispute resolution</h2>
                <p>
                    The European Commission provides a platform for online
                    dispute resolution (ODR):
                    <a
                        href="https://ec.europa.eu/consumers/odr/"
                        target="_blank"
                        rel="noopener"
                        >https://ec.europa.eu/consumers/odr/</a
                    >. You can find our email address above in this imprint.
                </p>

                <h2>Consumer dispute resolution</h2>
                <p>
                    We are not willing or obliged to participate in dispute
                    resolution proceedings before a consumer arbitration board.
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
