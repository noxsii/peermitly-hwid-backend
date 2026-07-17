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
        <!-- top bar -->
        <header
            class="border-border bg-background sticky top-0 z-40 h-20 border-b"
        >
            <div
                class="mx-auto flex h-20 max-w-[90rem] items-center gap-3 px-5 sm:px-8"
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
                        class="flex size-8 items-center justify-center transition-transform group-hover:rotate-3"
                    >
                        <LogoMark size="size-8" />
                    </span>
                    <span class="text-base font-semibold tracking-tight">
                        Peermitly
                    </span>
                    <span
                        class="text-muted-foreground ml-2 hidden border-l pl-4 text-[11px] font-semibold tracking-[0.16em] uppercase sm:inline"
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
                        <Button size="sm" class="rounded-md">
                            Dashboard
                        </Button>
                    </Link>
                    <Link v-else href="/login" class="hidden sm:block">
                        <Button size="sm" variant="ghost">Log in</Button>
                    </Link>
                </div>
            </div>
        </header>

        <div class="mx-auto flex max-w-[90rem] gap-10 px-5 sm:px-8 lg:gap-16">
            <!-- sidebar (desktop) -->
            <aside
                class="sticky top-20 hidden h-[calc(100vh-5rem)] w-60 shrink-0 overflow-y-auto border-r py-12 pr-8 lg:block"
            >
                <DocsSidebar :sections="sections" :current-slug="currentSlug" />
            </aside>

            <!-- main content -->
            <main class="min-w-0 flex-1 py-12 sm:py-16 lg:py-20">
                <div class="mx-auto max-w-4xl">
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
                class="sticky top-20 hidden h-[calc(100vh-5rem)] w-52 shrink-0 overflow-y-auto border-l py-12 pl-8 xl:block"
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
