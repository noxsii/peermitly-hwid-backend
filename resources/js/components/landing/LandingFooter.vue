<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import LogoMark from "@/components/Logo.vue";

const links = [
    { label: "Features", href: "/#features" },
    { label: "Free", href: "/#pricing" },
    { label: "FAQ", href: "/#faq" },
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
                        href="/news"
                        class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                    >
                        News
                    </Link>
                    <Link
                        href="/changelog"
                        class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                    >
                        Changelog
                    </Link>
                    <Link
                        href="/guide"
                        class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                    >
                        Docs
                    </Link>
                    <Link
                        href="/privacy"
                        class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                    >
                        Privacy
                    </Link>
                    <Link
                        href="/imprint"
                        class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                    >
                        Imprint
                    </Link>
                    <a
                        href="https://peermitly.betteruptime.com/"
                        target="_blank"
                        rel="noopener"
                        class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                    >
                        Status
                    </a>
                </nav>
            </div>

            <div
                class="border-border/60 text-muted-foreground mt-8 flex flex-col items-center justify-between gap-2 border-t pt-6 text-xs sm:flex-row"
            >
                <span
                    >© {{ new Date().getFullYear() }} Peermitly. All rights
                    reserved.</span
                >
                <span
                    class="border-border/60 bg-background/60 text-foreground/70 inline-flex items-center gap-2 rounded-full border px-3 py-1 font-medium"
                >
                    <span
                        class="flex h-3 w-4 flex-col overflow-hidden rounded-[2px]"
                        aria-hidden="true"
                    >
                        <span class="flex-1 bg-black" />
                        <span class="flex-1 bg-[#dd0000]" />
                        <span class="flex-1 bg-[#ffce00]" />
                    </span>
                    Made in Germany
                </span>
            </div>
        </div>
    </footer>
</template>
