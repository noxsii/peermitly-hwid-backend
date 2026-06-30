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

            ...
        </main>

        <footer
            class="border-border/60 text-muted-foreground mx-auto max-w-3xl border-t px-6 py-8 text-sm"
        >
            © {{ new Date().getFullYear() }} Peermitly
        </footer>
    </div>
</template>
