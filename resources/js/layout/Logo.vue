<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import LogoMark from "@/components/Logo.vue";
</script>

<template>
    <Link
        href="/dashboard"
        class="group focus-visible:ring-ring/40 inline-flex items-center gap-2.5 rounded-xl px-1 py-0.5 outline-none focus-visible:ring-2"
        aria-label="Peermitly home"
    >
        <LogoMark
            size="size-9"
            class="transition-transform duration-300 group-hover:scale-105 group-active:scale-95"
        />

        <span
            class="from-foreground to-foreground/70 dark:from-foreground dark:to-foreground/80 hidden bg-linear-to-r bg-clip-text text-lg font-semibold tracking-tight text-transparent sm:inline"
        >
            Peermitly
        </span>
    </Link>
</template>
