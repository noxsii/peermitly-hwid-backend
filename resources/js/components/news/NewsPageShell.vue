<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LogoMark from "@/components/Logo.vue";
</script>

<template>
    <div class="bg-background text-foreground min-h-screen">
        <header
            class="mx-auto flex max-w-5xl items-center justify-between px-6 py-5"
        >
            <Link href="/" class="flex items-center gap-2.5">
                <LogoMark size="size-9" />
                <span class="text-lg font-semibold tracking-tight">
                    Peermitly
                </span>
            </Link>
            <Link
                href="/login"
                class="text-muted-foreground hover:text-foreground text-sm transition-colors"
            >
                Log in
            </Link>
        </header>

        <slot />

        <LandingFooter />
    </div>
</template>
