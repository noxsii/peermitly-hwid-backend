<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { useWindowScroll } from "@vueuse/core";
import { computed } from "vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";
import type { PageProps } from "@/types";

const page = usePage<PageProps>();
const isAuthenticated = computed(() => page.props.auth?.user != null);

const { y } = useWindowScroll();
const scrolled = computed(() => y.value > 16);

const links = [
    { label: "Features", href: "#features" },
    { label: "How it works", href: "#how" },
    { label: "Stacks", href: "#stacks" },
    { label: "Pricing", href: "#pricing" },
    { label: "Docs", href: "/guide" },
    { label: "FAQ", href: "#faq" },
];
</script>

<template>
    <header
        class="fixed inset-x-0 top-0 z-50 transform-gpu transition-all duration-300 [will-change:transform]"
        :class="
            scrolled
                ? 'border-border/50 bg-background/70 border-b backdrop-blur-xl'
                : 'border-b border-transparent'
        "
    >
        <div
            class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6"
        >
            <Link href="/" class="group flex items-center gap-2.5">
                <span
                    class="ring-border/60 group-hover:ring-primary/40 flex size-9 items-center justify-center rounded-xl ring-1 transition-all group-hover:scale-105"
                >
                    <LogoMark size="size-9" />
                </span>
                <span class="text-lg font-semibold tracking-tight"
                    >Peermitly</span
                >
            </Link>

            <nav class="hidden items-center gap-1 md:flex">
                <a
                    v-for="link in links"
                    :key="link.href"
                    :href="link.href"
                    class="text-muted-foreground hover:text-foreground rounded-md px-3 py-2 text-sm font-medium transition-colors"
                >
                    {{ link.label }}
                </a>
            </nav>

            <div class="flex items-center gap-2">
                <Link v-if="isAuthenticated" href="/dashboard">
                    <Button size="sm">Dashboard</Button>
                </Link>
                <template v-else>
                    <Link href="/login" class="hidden sm:block">
                        <Button size="sm" variant="ghost">Log in</Button>
                    </Link>
                    <a href="#pricing">
                        <Button size="sm">Get access</Button>
                    </a>
                </template>
            </div>
        </div>
    </header>
</template>
