<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link
        href="/dashboard"
        class="group focus-visible:ring-ring/40 inline-flex items-center gap-2.5 rounded-xl px-1 py-0.5 outline-none focus-visible:ring-2"
        aria-label="Peermitly home"
    >
        <span
            class="from-primary via-primary to-info shadow-primary/25 ring-foreground/5 dark:ring-background/10 relative flex size-9 items-center justify-center overflow-hidden rounded-xl bg-linear-to-br shadow-lg ring-1 transition-transform duration-300 group-hover:scale-105 group-active:scale-95"
        >
            <span
                class="bg-background/95 dark:bg-foreground/90 absolute inset-0.75 rounded-full"
            />
            <span
                class="from-primary to-info relative bg-linear-to-br bg-clip-text text-base font-bold leading-none text-transparent"
            >
                p
            </span>
            <span
                class="pointer-events-none absolute inset-0 -translate-x-full rounded-xl bg-linear-to-r from-transparent via-white/30 to-transparent transition-transform duration-700 ease-out group-hover:translate-x-full"
            />
        </span>

        <span
            class="from-foreground to-foreground/70 dark:from-foreground dark:to-foreground/80 hidden bg-linear-to-r bg-clip-text text-lg font-semibold tracking-tight text-transparent sm:inline"
        >
            Peermitly
        </span>
    </Link>
</template>
