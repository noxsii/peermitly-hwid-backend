<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";

const links = [
    { label: "How it works", href: "#how" },
    { label: "API", href: "#api" },
    { label: "FAQ", href: "#faq" },
];
</script>

<template>
    <header
        class="border-border/60 bg-background/80 sticky top-0 z-30 w-full border-b backdrop-blur"
    >
        <div
            class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6"
        >
            <Link
                :href="route('home')"
                class="text-foreground inline-flex items-center gap-2 text-base font-semibold tracking-tight"
            >
                <span
                    class="bg-primary text-primary-foreground inline-flex size-7 items-center justify-center rounded-lg text-xs font-bold"
                    aria-hidden="true"
                    >P</span
                >
                Peermitly
            </Link>

            <nav
                class="text-muted-foreground absolute left-1/2 hidden -translate-x-1/2 items-center gap-7 text-sm md:flex"
            >
                <a
                    v-for="link in links"
                    :key="link.href"
                    :href="link.href"
                    class="hover:text-foreground transition-colors"
                >
                    {{ link.label }}
                </a>
            </nav>

            <Button as-child size="sm" variant="default">
                <Link :href="route('login')">Sign in</Link>
            </Button>
        </div>
    </header>
</template>
