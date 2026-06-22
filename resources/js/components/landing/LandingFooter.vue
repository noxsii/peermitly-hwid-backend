<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import LogoMark from "@/components/Logo.vue";

const links = [
    { label: "Features", href: "#features" },
    { label: "Pricing", href: "#pricing" },
    { label: "FAQ", href: "#faq" },
];
</script>

<template>
    <footer class="border-border/60 border-t">
        <div class="mx-auto max-w-6xl px-6 py-12">
            <div
                class="flex flex-col items-center justify-between gap-6 sm:flex-row"
            >
                <Link href="/" class="flex items-center gap-2.5">
                    <LogoMark size="size-8" />
                    <span class="font-semibold tracking-tight">Peermitly</span>
                </Link>

                <nav
                    class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2"
                >
                    <a
                        v-for="link in links"
                        :key="link.href"
                        :href="link.href"
                        class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                    >
                        {{ link.label }}
                    </a>
                    <Link
                        href="/privacy"
                        class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                    >
                        Privacy
                    </Link>
                </nav>
            </div>

            <div
                class="border-border/60 text-muted-foreground mt-8 flex flex-col items-center justify-between gap-2 border-t pt-6 text-xs sm:flex-row"
            >
                <span
                    >© {{ new Date().getFullYear() }} Peermitly. All rights
                    reserved.</span
                >
                <span>For educational and privacy purposes.</span>
            </div>
        </div>
    </footer>
</template>
