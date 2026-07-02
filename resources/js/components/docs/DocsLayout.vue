<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { Menu, Moon, Sun } from "@lucide/vue";
import { computed, ref, watch } from "vue";
import DocsContent from "@/components/docs/DocsContent.vue";
import DocsPager from "@/components/docs/DocsPager.vue";
import DocsSearch from "@/components/docs/DocsSearch.vue";
import DocsSidebar from "@/components/docs/DocsSidebar.vue";
import DocsToc from "@/components/docs/DocsToc.vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";
import { Sheet, SheetContent, SheetTitle } from "@/components/ui/sheet";
import { useAppearance } from "@/composables/useAppearance";
import type { DocPage, DocSection } from "@/types";
import type { PageProps } from "@/types";

const props = defineProps<{
    sections: DocSection[];
    currentSlug: string;
    page?: DocPage;
}>();

const inertiaPage = usePage<PageProps>();
const isAuthenticated = computed(() => inertiaPage.props.auth?.user != null);

const { toggle: toggleTheme } = useAppearance();

const mobileNav = ref(false);

watch(
    () => props.currentSlug,
    () => (mobileNav.value = false),
);
</script>

<template>
    <div
        class="bg-background text-foreground relative min-h-screen overflow-x-clip"
    >
        <!-- decorative background -->
        <div
            class="pointer-events-none fixed inset-0 -z-10 overflow-hidden"
            aria-hidden="true"
        >
            <div
                class="bg-primary/15 absolute -top-40 left-1/2 hidden h-[34rem] w-[34rem] -translate-x-1/2 rounded-full blur-[120px] sm:block"
            />
            <div
                class="bg-primary/10 absolute top-1/3 -right-40 hidden h-[26rem] w-[26rem] rounded-full blur-[120px] sm:block"
            />
            <div
                class="absolute inset-0 opacity-[0.18] [background-image:linear-gradient(to_right,color-mix(in_oklch,var(--border)_60%,transparent)_1px,transparent_1px),linear-gradient(to_bottom,color-mix(in_oklch,var(--border)_60%,transparent)_1px,transparent_1px)] [background-size:48px_48px] [mask-image:radial-gradient(ellipse_at_top,black,transparent_70%)]"
            />
        </div>

        <!-- top bar -->
        <header
            class="border-border/50 bg-background/70 transform-gpu sticky top-0 z-40 transform border-b backdrop-blur-xl [will-change:transform]"
        >
            <div
                class="mx-auto flex h-16 max-w-7xl items-center gap-3 px-4 sm:px-6"
            >
                <Button
                    variant="ghost"
                    size="icon-sm"
                    class="lg:hidden"
                    aria-label="Open navigation"
                    @click="mobileNav = true"
                >
                    <Menu />
                </Button>

                <Link href="/" class="group flex items-center gap-2.5">
                    <span
                        class="ring-border/60 group-hover:ring-primary/40 flex size-8 items-center justify-center rounded-xl ring-1 transition-all group-hover:scale-105"
                    >
                        <LogoMark size="size-8" />
                    </span>
                    <span class="text-base font-semibold tracking-tight">
                        Peermitly
                    </span>
                    <span
                        class="from-primary/15 to-primary/5 text-primary border-primary/20 ml-1 hidden rounded-full border bg-gradient-to-r px-2 py-0.5 text-xs font-semibold sm:inline"
                    >
                        Docs
                    </span>
                </Link>

                <div class="flex flex-1 items-center justify-end gap-1.5">
                    <DocsSearch :sections="sections" />

                    <Button
                        variant="ghost"
                        size="icon-sm"
                        aria-label="Toggle theme"
                        class="text-muted-foreground hover:text-foreground"
                        @click="toggleTheme"
                    >
                        <Sun class="hidden size-4 dark:block" />
                        <Moon class="size-4 dark:hidden" />
                    </Button>

                    <Link v-if="isAuthenticated" href="/dashboard">
                        <Button size="sm" class="shadow-primary/20 shadow-sm">
                            Dashboard
                        </Button>
                    </Link>
                    <Link v-else href="/login" class="hidden sm:block">
                        <Button size="sm" variant="ghost">Log in</Button>
                    </Link>
                </div>
            </div>
        </header>

        <div class="mx-auto flex max-w-7xl gap-8 px-4 sm:px-6 lg:gap-12">
            <!-- sidebar (desktop) -->
            <aside
                class="sticky top-16 hidden h-[calc(100vh-4rem)] w-56 shrink-0 overflow-y-auto py-10 lg:block"
            >
                <DocsSidebar :sections="sections" :current-slug="currentSlug" />
            </aside>

            <!-- main content -->
            <main class="min-w-0 flex-1 py-12">
                <div class="mx-auto max-w-3xl">
                    <DocsContent
                        v-if="page"
                        :key="currentSlug"
                        :html="page.html"
                    />
                    <p v-else class="text-muted-foreground">
                        This page could not be found.
                    </p>

                    <DocsPager
                        :sections="sections"
                        :current-slug="currentSlug"
                    />
                </div>
            </main>

            <!-- toc (desktop xl) -->
            <aside
                class="sticky top-16 hidden h-[calc(100vh-4rem)] w-56 shrink-0 overflow-y-auto py-12 xl:block"
            >
                <DocsToc
                    v-if="page"
                    :key="currentSlug"
                    :headings="page.headings"
                />
            </aside>
        </div>

        <!-- mobile nav -->
        <Sheet v-model:open="mobileNav">
            <SheetContent side="left" class="w-72 overflow-y-auto p-6">
                <SheetTitle class="mb-6 flex items-center gap-2.5">
                    <LogoMark size="size-7" />
                    <span class="font-semibold tracking-tight">Peermitly</span>
                </SheetTitle>
                <DocsSidebar
                    :sections="sections"
                    :current-slug="currentSlug"
                    @navigate="mobileNav = false"
                />
            </SheetContent>
        </Sheet>
    </div>
</template>
