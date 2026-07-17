<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { useWindowScroll } from "@vueuse/core";
import { Moon, Sun } from "@lucide/vue";
import { computed } from "vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";
import { useAppearance } from "@/composables/useAppearance";
import type { PageProps } from "@/types";

const page = usePage<PageProps>();
const isAuthenticated = computed(() => page.props.auth?.user != null);

const { toggle: toggleAppearance } = useAppearance();

const { y } = useWindowScroll();
const scrolled = computed(() => y.value > 16);

const links = [
    { label: "Features", href: "#features" },
    { label: "Product", href: "#product" },
    { label: "How it works", href: "#how" },
    { label: "Stacks", href: "#stacks" },
    { label: "Download", href: "#download" },
    { label: "Free", href: "#pricing" },
    { label: "Docs", href: "/guide" },
    { label: "FAQ", href: "#faq" },
];
</script>

<template>
    <header
        class="border-border bg-background/95 fixed inset-x-0 top-0 z-50 border-b shadow-sm backdrop-blur-md transition-[background-color,border-color,box-shadow] duration-200 md:shadow-none"
        :class="
            scrolled
                ? 'md:border-border md:bg-background/95 md:shadow-sm'
                : 'md:border-transparent md:bg-background/75'
        "
    >
        <div
            class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-2 px-4 sm:px-6"
        >
            <Link
                href="/"
                class="group flex min-w-0 items-center gap-2 sm:gap-2.5"
            >
                <span
                    class="flex size-9 shrink-0 items-center justify-center transition-transform group-hover:rotate-3"
                >
                    <LogoMark size="size-9" />
                </span>
                <span
                    class="hidden text-base font-semibold tracking-[-0.04em] min-[380px]:inline sm:text-lg"
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

            <div class="flex shrink-0 items-center gap-1 sm:gap-2">
                <Button
                    size="icon-sm"
                    variant="ghost"
                    class="rounded-full max-[360px]:hidden"
                    aria-label="Toggle dark mode"
                    @click="toggleAppearance"
                >
                    <Sun class="size-4 dark:hidden" />
                    <Moon class="hidden size-4 dark:block" />
                </Button>

                <Link v-if="isAuthenticated" href="/dashboard">
                    <Button size="sm">Dashboard</Button>
                </Link>
                <template v-else>
                    <Link href="/login" class="hidden sm:block">
                        <Button size="sm" variant="ghost">Log in</Button>
                    </Link>
                    <Link href="/register">
                        <Button size="sm" class="whitespace-nowrap"
                            >Sign up free</Button
                        >
                    </Link>
                </template>
            </div>
        </div>
    </header>
</template>
