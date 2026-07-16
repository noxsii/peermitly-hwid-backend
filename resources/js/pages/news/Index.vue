<script setup lang="ts">
import { Head, InfiniteScroll, Link } from "@inertiajs/vue3";
import { Newspaper } from "@lucide/vue";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LogoMark from "@/components/Logo.vue";
import type { News } from "@/types";

defineOptions({ layout: "" });

defineProps<{
    entries: { data: News[] };
}>();

const formatDate = (iso: string | null): string => {
    if (!iso) return "";
    try {
        return new Date(iso).toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    } catch {
        return "";
    }
};
</script>

<template>
    <Head title="News — Peermitly">
        <meta
            name="description"
            content="Product news, announcements and stories from the Peermitly team."
            head-key="description"
        />
    </Head>

    <div class="bg-background text-foreground min-h-screen">
        <header
            class="mx-auto flex max-w-5xl items-center justify-between px-6 py-5"
        >
            <Link href="/" class="flex items-center gap-2.5">
                <LogoMark size="size-9" />
                <span class="text-lg font-semibold tracking-tight"
                    >Peermitly</span
                >
            </Link>
            <Link
                href="/login"
                class="text-muted-foreground hover:text-foreground text-sm"
                >Log in</Link
            >
        </header>

        <main class="mx-auto max-w-5xl px-6 py-16 md:py-24">
            <header class="mb-16 space-y-3">
                <p
                    class="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase"
                >
                    What's new
                </p>
                <h1
                    class="text-foreground text-4xl font-semibold tracking-tight md:text-5xl"
                >
                    News
                </h1>
                <p class="text-muted-foreground max-w-xl text-sm leading-6">
                    Product news, announcements and stories from the Peermitly
                    team.
                </p>
            </header>

            <InfiniteScroll
                v-if="entries.data.length"
                data="entries"
                only-next
                preserve-url
            >
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="entry in entries.data"
                        :key="entry.uuid"
                        :href="`/news/${entry.slug}`"
                        class="group border-border/60 bg-card hover:border-primary/40 flex flex-col overflow-hidden rounded-xl border transition-colors"
                    >
                        <div
                            class="bg-muted aspect-video w-full overflow-hidden"
                        >
                            <img
                                v-if="entry.image_url"
                                :src="entry.image_url"
                                :alt="entry.title"
                                loading="lazy"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                v-else
                                class="from-primary/20 to-primary/5 h-full w-full bg-gradient-to-br"
                                aria-hidden="true"
                            />
                        </div>
                        <div class="flex flex-1 flex-col gap-2 p-5">
                            <time
                                v-if="entry.published_at"
                                :datetime="entry.published_at"
                                class="text-muted-foreground text-xs"
                            >
                                {{ formatDate(entry.published_at) }}
                            </time>
                            <h2
                                class="text-foreground group-hover:text-primary text-lg font-semibold tracking-tight transition-colors"
                            >
                                {{ entry.title }}
                            </h2>
                            <p
                                class="text-muted-foreground line-clamp-3 text-sm leading-6"
                            >
                                {{ entry.description }}
                            </p>
                        </div>
                    </Link>
                </div>

                <template #loading>
                    <div
                        class="text-muted-foreground flex items-center justify-center gap-2 py-10 text-sm"
                    >
                        <span
                            class="border-primary size-4 animate-spin rounded-full border-2 border-t-transparent"
                            aria-hidden="true"
                        />
                        Loading more news…
                    </div>
                </template>
            </InfiniteScroll>

            <div
                v-else
                class="border-border/60 flex flex-col items-center gap-2 rounded-xl border py-16 text-center"
            >
                <Newspaper
                    class="text-muted-foreground size-8"
                    aria-hidden="true"
                />
                <p class="text-foreground text-sm font-medium">No news yet.</p>
                <p class="text-muted-foreground text-xs">
                    New articles will appear here as soon as they are published.
                </p>
            </div>
        </main>

        <LandingFooter />
    </div>
</template>
