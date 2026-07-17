<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import LogoMark from "@/components/Logo.vue";
</script>

<template>
    <Link
        href="/dashboard"
        class="group focus-visible:ring-ring/40 inline-flex items-center gap-2.5 outline-none focus-visible:ring-2"
        aria-label="Peermitly home"
    >
        <LogoMark
            size="size-9"
            class="transition-transform duration-300 group-hover:rotate-3 group-active:scale-95"
        />

        <span class="hidden text-lg font-semibold tracking-[-0.04em] sm:inline">
            Peermitly
        </span>
    </Link>
</template>
