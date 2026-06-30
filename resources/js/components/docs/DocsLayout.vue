<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { Menu } from "@lucide/vue";
import { computed, ref, watch } from "vue";
import DocsContent from "@/components/docs/DocsContent.vue";
import DocsPager from "@/components/docs/DocsPager.vue";
import DocsSearch from "@/components/docs/DocsSearch.vue";
import DocsSidebar from "@/components/docs/DocsSidebar.vue";
import DocsToc from "@/components/docs/DocsToc.vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";
import { Sheet, SheetContent, SheetTitle } from "@/components/ui/sheet";
import type { DocPage, DocSection } from "@/types";
import type { PageProps } from "@/types";

const props = defineProps<{
    sections: DocSection[];
    currentSlug: string;
    page?: DocPage;
}>();

const inertiaPage = usePage<PageProps>();
const isAuthenticated = computed(() => inertiaPage.props.auth?.user != null);

const mobileNav = ref(false);

watch(
    () => props.currentSlug,
    () => (mobileNav.value = false),
);
</script>

<template>
    <div class="bg-background text-foreground min-h-screen">
        <!-- top bar -->
        <header
            class="border-border/60 bg-background/80 transform-gpu sticky top-0 z-40 transform border-b backdrop-blur-xl [will-change:transform]"
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

                <Link href="/" class="flex items-center gap-2.5">
                    <LogoMark size="size-8" />
                    <span class="text-base font-semibold tracking-tight">
                        Peermitly
                    </span>
                    <span
                        class="border-border/60 text-muted-foreground ml-1 hidden rounded-full border px-2 py-0.5 text-xs font-medium sm:inline"
                    >
                        Docs
                    </span>
                </Link>

                <div class="flex flex-1 items-center justify-end gap-2">
                    <DocsSearch :sections="sections" />
                    <Link v-if="isAuthenticated" href="/dashboard">
                        <Button size="sm">Dashboard</Button>
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
            <main class="min-w-0 flex-1 py-10">
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
                class="sticky top-16 hidden h-[calc(100vh-4rem)] w-56 shrink-0 overflow-y-auto py-10 xl:block"
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
