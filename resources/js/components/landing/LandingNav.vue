<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { Menu, Moon, Newspaper, Sun } from "@lucide/vue";
import { computed, ref } from "vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";
import { Sheet, SheetContent, SheetTitle } from "@/components/ui/sheet";
import { useAppearance } from "@/composables/useAppearance";
import type { PageProps } from "@/types";

const page = usePage<PageProps>();
const isAuthenticated = computed(() => page.props.auth?.user != null);

const { toggle: toggleAppearance } = useAppearance();

const desktopLinks = [
    { label: "Features", href: "#features" },
    { label: "Product", href: "#product" },
    { label: "How it works", href: "#how" },
    { label: "Stacks", href: "#stacks" },
    { label: "Download", href: "#download" },
    { label: "Pricing", href: "#pricing" },
    { label: "Docs", href: "/guide" },
    { label: "News", href: "/news" },
    { label: "FAQ", href: "#faq" },
];

const mobileLinks = [...desktopLinks];

const mobileNav = ref(false);

function closeMobileNav(): void {
    mobileNav.value = false;
}
</script>

<template>
    <header
        class="border-border bg-background fixed inset-x-0 top-0 z-50 h-16 border-b"
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

            <nav
                class="hidden items-center gap-1 min-[1180px]:flex"
                aria-label="Main navigation"
            >
                <template v-for="link in desktopLinks" :key="link.href">
                    <Link
                        v-if="link.href.startsWith('/')"
                        :href="link.href"
                        prefetch
                        class="text-muted-foreground hover:text-foreground border-b border-transparent px-2.5 py-2 text-xs font-medium transition-colors hover:border-primary/60 xl:px-3 xl:text-sm"
                    >
                        {{ link.label }}
                    </Link>
                    <a
                        v-else
                        :href="link.href"
                        class="text-muted-foreground hover:text-foreground border-b border-transparent px-2.5 py-2 text-xs font-medium transition-colors hover:border-primary/60 xl:px-3 xl:text-sm"
                    >
                        {{ link.label }}
                    </a>
                </template>
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

                <Button
                    size="icon-sm"
                    variant="ghost"
                    class="rounded-full min-[1180px]:hidden"
                    aria-label="Open menu"
                    @click="mobileNav = true"
                >
                    <Menu class="size-5" />
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

    <Sheet v-model:open="mobileNav">
        <SheetContent
            side="right"
            class="w-[min(88vw,22rem)] gap-0 p-0"
            aria-describedby="landing-menu-description"
        >
            <div
                class="border-border flex items-center gap-3 border-b px-6 py-5"
            >
                <LogoMark size="size-9" />
                <div>
                    <SheetTitle class="text-base tracking-[-0.03em]">
                        Peermitly
                    </SheetTitle>
                    <p
                        id="landing-menu-description"
                        class="text-muted-foreground text-xs"
                    >
                        Local development, simplified.
                    </p>
                </div>
            </div>

            <nav
                class="flex flex-1 flex-col overflow-y-auto px-3 py-4"
                aria-label="Mobile navigation"
            >
                <template v-for="link in mobileLinks" :key="link.href">
                    <Link
                        v-if="link.href.startsWith('/')"
                        :href="link.href"
                        prefetch
                        class="hover:bg-muted flex items-center justify-between rounded-md px-4 py-3 text-base font-medium transition-colors"
                        @click="closeMobileNav"
                    >
                        <span class="flex items-center gap-3">
                            <Newspaper
                                v-if="link.href === '/news'"
                                class="text-primary size-4"
                            />
                            {{ link.label }}
                        </span>
                        <span class="text-muted-foreground text-xs">↗</span>
                    </Link>
                    <a
                        v-else
                        :href="link.href"
                        class="hover:bg-muted flex items-center justify-between rounded-md px-4 py-3 text-base font-medium transition-colors"
                        @click="closeMobileNav"
                    >
                        {{ link.label }}
                        <span class="text-muted-foreground text-xs">↓</span>
                    </a>
                </template>
            </nav>

            <div class="border-border grid gap-2 border-t p-4">
                <Link
                    v-if="isAuthenticated"
                    href="/dashboard"
                    @click="closeMobileNav"
                >
                    <Button class="w-full">Open dashboard</Button>
                </Link>
                <template v-else>
                    <Link href="/register" @click="closeMobileNav">
                        <Button class="w-full">Sign up free</Button>
                    </Link>
                    <Link href="/login" @click="closeMobileNav">
                        <Button variant="outline" class="w-full">
                            Member login
                        </Button>
                    </Link>
                </template>
            </div>
        </SheetContent>
    </Sheet>
</template>
